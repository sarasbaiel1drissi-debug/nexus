# Diagrammes UML - Nexus

Ce document contient les diagrammes UML en francais pour l'application Nexus.
Les diagrammes sont ecrits en PlantUML afin de pouvoir etre exportes en PNG, SVG ou PDF.

## 1. Diagramme de cas d'utilisation

```plantuml
@startuml
left to right direction
skinparam packageStyle rectangle
skinparam actorStyle awesome

actor "Visiteur" as Visiteur
actor "Utilisateur authentifie" as Utilisateur
actor "Administrateur" as Admin

Admin --|> Utilisateur

rectangle "Systeme Nexus" {
  package "Authentification" {
    usecase "S'inscrire" as UC_Register
    usecase "Se connecter" as UC_Login
    usecase "Se deconnecter" as UC_Logout
    usecase "Reinitialiser le mot de passe" as UC_ResetPassword
  }

  package "Profil et decouverte" {
    usecase "Gerer son profil" as UC_Profile
    usecase "Consulter un profil public" as UC_PublicProfile
    usecase "Rechercher des joueurs" as UC_FindPlayers
  }

  package "Fil d'actualite et publications" {
    usecase "Consulter le fil d'actualite" as UC_Feed
    usecase "Publier un post" as UC_CreatePost
    usecase "Ajouter des medias au post" as UC_AddMedia
    usecase "Supprimer son post" as UC_DeletePost
    usecase "Aimer / retirer un j'aime" as UC_Like
    usecase "Commenter un post" as UC_Comment
  }

  package "Relations sociales" {
    usecase "Suivre / ne plus suivre un utilisateur" as UC_Follow
    usecase "Envoyer un uplift" as UC_Uplift
    usecase "Consulter les notifications" as UC_Notifications
    usecase "Gerer ses listes d'audience" as UC_Audience
  }

  package "Messagerie" {
    usecase "Gerer les conversations" as UC_Conversations
    usecase "Lire les messages" as UC_ReadMessage
    usecase "Envoyer un message" as UC_SendMessage
  }

  package "Gamification" {
    usecase "Consulter les taches quotidiennes" as UC_Tasks
    usecase "Valider une tache" as UC_ToggleTask
    usecase "Gagner de l'XP" as UC_XP
    usecase "Obtenir des badges" as UC_Badges
  }

  package "Administration" {
    usecase "Consulter le tableau de bord admin" as UC_AdminDashboard
    usecase "Administrer les tables" as UC_AdminTables
    usecase "Gerer les utilisateurs" as UC_AdminUsers
    usecase "Gerer les badges" as UC_AdminBadges
  }
}

Visiteur --> UC_Register
Visiteur --> UC_Login
Visiteur --> UC_ResetPassword
Visiteur --> UC_PublicProfile

Utilisateur --> UC_Feed
Utilisateur --> UC_Profile
Utilisateur --> UC_PublicProfile
Utilisateur --> UC_Logout
Utilisateur --> UC_CreatePost
Utilisateur --> UC_DeletePost
Utilisateur --> UC_Like
Utilisateur --> UC_Comment
Utilisateur --> UC_Follow
Utilisateur --> UC_Uplift
Utilisateur --> UC_Notifications
Utilisateur --> UC_FindPlayers
Utilisateur --> UC_Conversations
Utilisateur --> UC_SendMessage
Utilisateur --> UC_ReadMessage
Utilisateur --> UC_Audience
Utilisateur --> UC_Tasks
Utilisateur --> UC_ToggleTask

Admin --> UC_AdminDashboard
Admin --> UC_AdminTables
Admin --> UC_AdminUsers
Admin --> UC_AdminBadges

UC_CreatePost ..> UC_AddMedia : <<extend>>
UC_SendMessage ..> UC_Conversations : <<include>>
UC_ReadMessage ..> UC_Conversations : <<include>>
UC_Follow ..> UC_Notifications : <<extend>>
UC_Uplift ..> UC_Notifications : <<extend>>
UC_ToggleTask ..> UC_XP : <<include>>
UC_XP ..> UC_Badges : <<extend>>
UC_AdminTables ..> UC_AdminUsers : <<include>>
UC_AdminTables ..> UC_AdminBadges : <<include>>
@enduml
```

## 2. Diagrammes de sequence

### 2.1 Creation d'un post avec media

```plantuml
@startuml
actor "Utilisateur" as U
boundary "Interface web" as UI
control "PostController" as PC
entity "Post" as Post
entity "PostMedia" as Media
control "Stockage public" as Storage
control "TaskAutoCompleteService" as TaskService

U -> UI : Saisir contenu, confidentialite et medias
UI -> PC : POST /posts
PC -> PC : Valider contenu, privacy et fichiers

alt Donnees invalides
  PC --> UI : Retour avec erreurs
else Donnees valides
  PC -> Post : Creer le post
  loop Pour chaque media
    PC -> Storage : Enregistrer le fichier
    Storage --> PC : Chemin du fichier
    PC -> Media : Creer l'enregistrement media
  end
  PC -> TaskService : Verifier action "post"
  TaskService --> PC : Taches/XP mis a jour si necessaire
  PC --> UI : Retour avec message de succes
end
@enduml
```

### 2.2 Aimer ou retirer un j'aime sur un post

```plantuml
@startuml
actor "Utilisateur" as Utilisateur
boundary "Interface web" as Interface
control "PostController" as Controller
entity "Post" as Post
entity "Like" as Like
control "TaskAutoCompleteService" as TaskService

Utilisateur -> Interface : Cliquer sur le bouton j'aime
Interface -> Controller : POST /posts/{post}/like
Controller -> Post : Rechercher le post concerne

alt Post introuvable
  Controller --> Interface : JSON erreur 404
else Post trouve
  Controller -> Like : Verifier si un j'aime existe deja\npour cet utilisateur et ce post

  alt J'aime existe deja
    Controller -> Like : Supprimer le j'aime
    Controller -> Post : Recalculer likes_count
    Controller --> Interface : JSON liked=false\n+ nouveau likes_count
  else Aucun j'aime existant
    Controller -> Like : Creer un nouvel enregistrement
    Controller -> Post : Mettre a jour likes_count
    Controller -> TaskService : Verifier action "like"
    TaskService --> Controller : Taches/XP mis a jour si necessaire
    Controller --> Interface : JSON liked=true\n+ nouveau likes_count
  end
end
@enduml
```

### 2.3 Envoyer un message dans une conversation

```plantuml
@startuml
actor "Utilisateur expediteur" as Sender
actor "Utilisateur destinataire" as Receiver
boundary "Interface messagerie\n(expediteur)" as SenderUI
boundary "Interface messagerie\n(destinataire)" as ReceiverUI
control "ChatController" as Chat
entity "Conversation" as Conversation
entity "Message" as Message
control "TaskAutoCompleteService" as TaskService
control "MessageSent" as Event
queue "Laravel Reverb\nWebSocket" as Reverb
control "Canal prive\nconversations.{id}" as Channel

ReceiverUI -> Reverb : Ecouter le canal prive
Reverb -> Channel : Demande d'abonnement
Channel -> Conversation : Verifier que l'utilisateur\nappartient a la conversation

alt Abonnement refuse
  Channel --> Reverb : Refus d'acces
  Reverb --> ReceiverUI : Non abonne au canal
else Abonnement autorise
  Channel --> Reverb : Autorisation du canal
  Reverb --> ReceiverUI : Abonnement actif
end

Sender -> SenderUI : Rediger et envoyer un message
SenderUI -> Chat : POST /chat/conversations/{conversation}/messages
Chat -> Conversation : Verifier que l'expediteur\nappartient a la conversation

alt Acces interdit
  Chat --> SenderUI : Erreur 403
else Acces autorise
  Chat -> Chat : Valider le corps du message
  Chat -> Message : Creer le message
  Chat -> Conversation : Mettre a jour updated_at
  Chat -> TaskService : Verifier action "message"
  TaskService --> Chat : Taches/XP mis a jour si necessaire
  Chat -> Event : Creer l'evenement MessageSent
  Event -> Reverb : Diffuser sur conversations.{id}
  Reverb -> ReceiverUI : Envoyer le nouveau message\nen temps reel
  Chat --> SenderUI : JSON du message cree
end
@enduml
```

### 2.4 Suivre un utilisateur et creer une notification

```plantuml
@startuml
actor "Utilisateur" as U
boundary "Interface web" as UI
control "SocialController" as SC
entity "User" as Target
entity "Follow" as Follow
entity "Notification" as Notification
control "TaskAutoCompleteService" as TaskService

U -> UI : Cliquer suivre / ne plus suivre
UI -> SC : POST /follow/{user}
SC -> Target : Verifier l'utilisateur cible

alt L'utilisateur se suit lui-meme
  SC --> UI : Erreur 403
else Cible valide
  SC -> Follow : Chercher relation existante
  alt Relation existe
    SC -> Follow : Supprimer la relation
    SC --> UI : Retour "Unfollowed"
  else Relation absente
    SC -> Follow : Creer la relation
    SC -> Notification : Creer notification de type follow
    SC -> TaskService : Verifier action "follow"
    SC --> UI : Retour "Following"
  end
end
@enduml
```

### 2.5 Attribution des taches quotidiennes et XP

```plantuml
@startuml
actor "Utilisateur" as U
boundary "Page taches" as UI
control "TasksController" as TC
entity "DailyTask" as DailyTask
entity "UserDailyTask" as UserTask
control "TaskAutoCompleteService" as TaskService
control "BadgeService" as BadgeService
entity "Badge" as Badge

U -> UI : Ouvrir la page des taches
UI -> TC : GET /tasks
TC -> UserTask : Chercher les taches du jour

alt Aucune tache assignee aujourd'hui
  TC -> DailyTask : Choisir 5 taches aleatoires
  loop Pour chaque tache
    TC -> UserTask : Assigner la tache a l'utilisateur
  end
  TC -> TaskService : Verifier la tache de connexion
  TaskService -> UserTask : Marquer comme completee si applicable
  TaskService -> BadgeService : Verifier badges lies a l'XP
  BadgeService -> Badge : Chercher badges eligibles
else Taches deja assignees
  TC -> UserTask : Charger les taches avec details
end

TC --> UI : Afficher progression, taches et XP
@enduml
```

## 3. Diagramme de classes

```plantuml
@startuml
skinparam classAttributeIconSize 0
hide circle

class User {
  +id: int
  +name: string
  +username: string
  +email: string
  +bio: text
  +location: string
  +birthdate: date
  +gender: string
  +profile_picture: string
  +cover_picture: string
  +playstyle_tags: array
  +languages: string
  +region: string
  +availability: string
  +is_online: boolean
  +is_admin: boolean
  +xp: int
  +theme_color: string
  +isFollowing(userId): boolean
  +hasUplifted(userId): boolean
  +getProfilePictureUrlAttribute()
  +getUnreadMessageCountAttribute()
}

class Post {
  +id: int
  +user_id: int
  +content: text
  +privacy: string
  +likes_count: int
  +comments_count: int
  +isLikedBy(userId): boolean
  +scopeVisibleTo(query, viewer)
}

class PostMedia {
  +id: int
  +post_id: int
  +file_path: string
  +file_type: string
  +mime_type: string
  +file_size: int
  +order: int
  +metadata: array
  +getUrlAttribute()
  +getDimensions()
}

class Comment {
  +id: int
  +user_id: int
  +post_id: int
  +content: text
}

class Like {
  +id: int
  +user_id: int
  +post_id: int
}

class Follow {
  +id: int
  +follower_id: int
  +following_id: int
}

class Uplift {
  +id: int
  +uplifter_id: int
  +uplifted_id: int
}

class Notification {
  +id: int
  +user_id: int
  +from_user_id: int
  +post_id: int
  +type: string
  +read: boolean
}

class Conversation {
  +id: int
  +user_one_id: int
  +user_two_id: int
}

class Message {
  +id: int
  +conversation_id: int
  +sender_id: int
  +body: text
  +read_at: datetime
}

class Game {
  +id: int
  +name: string
  +image_url: string
  +slug: string
}

class Badge {
  +id: int
  +name: string
  +criteria: array
}

class BadgeUser {
  +user_id: int
  +badge_id: int
  +earned_at: datetime
  +expires_at: datetime
}

class DailyTask {
  +id: int
  +title: string
  +description: text
  +xp: int
  +category: string
  +auto_complete_rule: string
}

class UserDailyTask {
  +id: int
  +user_id: int
  +daily_task_id: int
  +assigned_date: date
  +completed: boolean
}

class AudienceList {
  +id: int
  +user_id: int
  +name: string
}

class AudienceListMember {
  +id: int
  +audience_list_id: int
  +user_id: int
}

class TaskAutoCompleteService {
  +check(user, actionType): void
  +checkLogin(user): void
  +checkAllOthersDone(user): void
  +handleManualToggle(task, user): void
  -countTodayActions(user, actionType, today): int
  -awardXp(user, amount): void
  -deductXp(user, amount): void
}

class BadgeService {
  +awardNewUserBadge(user): void
  +checkAndAwardXpBadges(user): void
}

User "1" --> "0..*" Post : publie
User "1" --> "0..*" Comment : ecrit
User "1" --> "0..*" Like : aime
Post "1" --> "0..*" PostMedia : contient
Post "1" --> "0..*" Comment : recoit
Post "1" --> "0..*" Like : recoit

User "1" --> "0..*" Follow : suit
User "1" --> "0..*" Follow : est suivi
User "1" --> "0..*" Uplift : donne
User "1" --> "0..*" Uplift : recoit
User "1" --> "0..*" Notification : recoit
Notification "0..*" --> "0..1" Post : concerne

User "1" --> "0..*" Conversation : user_one
User "1" --> "0..*" Conversation : user_two
Conversation "1" --> "0..*" Message : contient
User "1" --> "0..*" Message : envoie

User "0..*" -- "0..*" Game : joue
User "0..*" -- "0..*" Badge : possede
BadgeUser .. User
BadgeUser .. Badge

User "1" --> "0..*" UserDailyTask : realise
DailyTask "1" --> "0..*" UserDailyTask : est assignee
User "1" --> "0..*" AudienceList : cree
AudienceList "1" --> "0..*" AudienceListMember : contient
AudienceListMember "0..*" --> "1" User : membre

TaskAutoCompleteService ..> UserDailyTask : complete
TaskAutoCompleteService ..> BadgeService : declenche
BadgeService ..> Badge : attribue
@enduml
```
