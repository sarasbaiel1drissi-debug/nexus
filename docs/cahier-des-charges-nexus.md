# Cahier des charges - Nexus

## 1. Presentation du projet

Nexus est une application web sociale orientee gaming et interaction entre utilisateurs. Elle permet aux membres de creer un profil, publier du contenu, interagir avec d'autres utilisateurs, echanger des messages, suivre des joueurs, consulter des notifications et realiser des taches quotidiennes afin de gagner de l'XP et des badges.

Le projet vise a proposer une plateforme centralisee ou les utilisateurs peuvent construire leur identite numerique, partager leurs activites, decouvrir d'autres joueurs et renforcer leur engagement grace a un systeme de gamification.

## 2. Contexte du projet

Les plateformes sociales classiques ne sont pas toujours adaptees aux besoins des joueurs, notamment en ce qui concerne la recherche de profils compatibles, la valorisation de l'activite, les badges, les preferences de jeu et la messagerie orientee communaute.

Nexus repond a ce besoin en combinant les fonctionnalites d'un reseau social avec des elements propres a l'univers gaming : profils personnalises, jeux favoris, tags de style de jeu, taches quotidiennes, XP, badges et interactions sociales rapides.

## 3. Objectifs du projet

L'objectif principal de Nexus est de developper une plateforme web permettant aux utilisateurs de communiquer, publier du contenu et interagir dans un environnement social gamifie.

Les objectifs specifiques sont :

- Permettre aux utilisateurs de s'inscrire et de se connecter de maniere securisee.
- Offrir un espace personnel de gestion de profil.
- Permettre la publication de posts avec texte et medias.
- Permettre les interactions sociales : j'aime, commentaires, follow et uplift.
- Mettre en place une messagerie entre utilisateurs.
- Proposer un systeme de notifications.
- Integrer un systeme de taches quotidiennes avec gain d'XP.
- Attribuer des badges selon l'activite et la progression de l'utilisateur.
- Fournir une interface d'administration pour gerer les donnees principales.

## 4. Perimetre du projet

### 4.1 Fonctionnalites incluses

Le projet couvre les modules suivants :

- Authentification et gestion de compte.
- Gestion du profil utilisateur.
- Fil d'actualite.
- Publications avec medias.
- Likes et commentaires.
- Follow, uplift et notifications.
- Messagerie entre utilisateurs.
- Recherche ou decouverte de joueurs.
- Gestion des listes d'audience.
- Taches quotidiennes.
- XP et badges.
- Administration des utilisateurs, posts, badges, taches et autres tables.

### 4.2 Fonctionnalites exclues

Les fonctionnalites suivantes ne font pas partie du perimetre principal :

- Application mobile native.
- Paiement en ligne.
- Systeme de tournois complet.
- Appels audio ou video.
- Moderation automatique avancee par intelligence artificielle.
- Systeme complet de recommandations algorithmiques.

## 5. Acteurs du systeme

### 5.1 Visiteur

Le visiteur est une personne non connectee. Il peut acceder a la page de connexion, creer un compte et consulter certains profils publics.

### 5.2 Utilisateur authentifie

L'utilisateur authentifie est le membre principal de la plateforme. Il peut gerer son profil, publier du contenu, interagir avec les publications, suivre d'autres utilisateurs, envoyer des messages, consulter ses notifications, realiser ses taches quotidiennes et gagner de l'XP.

### 5.3 Administrateur

L'administrateur dispose de droits supplementaires. Il peut acceder a l'espace d'administration afin de consulter, ajouter, modifier ou supprimer certaines donnees du systeme, comme les utilisateurs, les posts, les badges et les taches.

## 6. Besoins fonctionnels

### 6.1 Authentification

Le systeme doit permettre :

- L'inscription d'un nouvel utilisateur.
- La connexion avec email et mot de passe.
- La deconnexion.
- La verification de l'adresse email.
- La reinitialisation du mot de passe.
- La protection des pages reservees aux utilisateurs connectes.

### 6.2 Gestion du profil

L'utilisateur doit pouvoir :

- Modifier ses informations personnelles.
- Ajouter ou modifier une photo de profil.
- Ajouter ou modifier une image de couverture.
- Renseigner une biographie.
- Indiquer sa localisation, sa region, ses langues et ses disponibilites.
- Personnaliser certains elements visuels de son profil.
- Consulter le profil public d'autres utilisateurs.

### 6.3 Fil d'actualite

Le systeme doit permettre :

- L'affichage des publications accessibles a l'utilisateur.
- Le chargement des informations de l'auteur du post.
- L'affichage des medias, commentaires et compteurs d'interaction.
- Le respect de la confidentialite des publications.

### 6.4 Gestion des publications

L'utilisateur doit pouvoir :

- Creer une publication avec contenu texte.
- Ajouter un ou plusieurs medias a une publication.
- Definir la confidentialite de la publication.
- Supprimer ses propres publications.

Le systeme doit :

- Valider le contenu envoye.
- Valider les types et tailles de fichiers.
- Stocker les medias dans l'espace public.
- Associer chaque media a la publication correspondante.

### 6.5 Likes et commentaires

L'utilisateur doit pouvoir :

- Aimer une publication.
- Retirer son j'aime.
- Commenter une publication.

Le systeme doit :

- Eviter les doublons de j'aime pour un meme utilisateur.
- Mettre a jour le compteur de likes.
- Mettre a jour le compteur de commentaires.
- Verifier si ces actions completent des taches quotidiennes.

### 6.6 Relations sociales

L'utilisateur doit pouvoir :

- Suivre un autre utilisateur.
- Ne plus suivre un utilisateur.
- Envoyer ou retirer un uplift.
- Consulter ses abonnes, abonnements et relations mutuelles.

Le systeme doit :

- Interdire a un utilisateur de se suivre lui-meme.
- Creer une notification lorsqu'un utilisateur est suivi.
- Creer une notification lorsqu'un utilisateur recoit un uplift.

### 6.7 Messagerie

L'utilisateur doit pouvoir :

- Ouvrir une conversation avec un autre utilisateur.
- Consulter ses conversations.
- Lire les messages d'une conversation.
- Envoyer un message.
- Marquer les messages comme lus.

Le systeme doit :

- Verifier que l'utilisateur appartient a la conversation.
- Enregistrer les messages envoyes.
- Mettre a jour la date de la conversation.
- Retourner les messages sous forme de donnees JSON.
- Diffuser un evenement lors de l'envoi d'un message.

### 6.8 Notifications

L'utilisateur doit pouvoir :

- Consulter ses notifications.
- Identifier l'utilisateur a l'origine de la notification.

Le systeme doit :

- Marquer les notifications comme lues apres consultation.
- Associer une notification a un utilisateur cible.
- Associer certaines notifications a un utilisateur source ou a une publication.

### 6.9 Taches quotidiennes

L'utilisateur doit pouvoir :

- Consulter les taches du jour.
- Voir sa progression quotidienne.
- Valider manuellement certaines taches.

Le systeme doit :

- Assigner automatiquement des taches quotidiennes.
- Selectionner des taches aleatoires si aucune tache n'est deja assignee.
- Completer automatiquement certaines taches selon les actions de l'utilisateur.
- Ajouter ou retirer l'XP selon l'etat de completion.
- Gerer les taches speciales, comme la completion de toutes les autres taches.

### 6.10 XP et badges

Le systeme doit :

- Ajouter de l'XP lorsqu'une tache est completee.
- Eviter que l'XP devienne negative.
- Verifier les badges disponibles selon les criteres.
- Attribuer automatiquement un badge lorsque l'utilisateur atteint les conditions requises.
- Enregistrer la date d'obtention et eventuellement la date d'expiration d'un badge.

### 6.11 Listes d'audience

L'utilisateur doit pouvoir :

- Creer une liste d'audience personnalisee.
- Supprimer une liste qu'il possede.
- Ajouter un utilisateur a une liste.
- Retirer un utilisateur d'une liste.

Le systeme doit :

- Verifier que la liste appartient bien a l'utilisateur connecte.
- Eviter les ajouts en double dans une meme liste.

### 6.12 Administration

L'administrateur doit pouvoir :

- Acceder au tableau de bord d'administration.
- Consulter les statistiques principales.
- Consulter les tables autorisees.
- Ajouter des enregistrements.
- Modifier des enregistrements.
- Supprimer des enregistrements.

Le systeme doit :

- Restreindre l'acces aux administrateurs uniquement.
- Valider les tables autorisees avant toute operation.
- Gerer les champs sensibles comme les mots de passe.
- Gerer les colonnes booleennes et JSON.

## 7. Besoins non fonctionnels

### 7.1 Securite

Le systeme doit :

- Proteger les routes privees par authentification.
- Proteger l'espace d'administration par un role administrateur.
- Hasher les mots de passe.
- Verifier les autorisations sur les conversations, listes d'audience et publications.
- Valider les donnees envoyees par l'utilisateur.
- Limiter les types et tailles des fichiers uploades.

### 7.2 Performance

Le systeme doit :

- Charger les donnees importantes avec leurs relations lorsque c'est necessaire.
- Paginer les grandes listes dans l'administration et les notifications.
- Eviter les requetes inutiles.
- Mettre a jour uniquement les donnees necessaires.

### 7.3 Ergonomie

L'interface doit etre :

- Simple a utiliser.
- Claire pour les utilisateurs non techniques.
- Adaptee aux actions frequentes : publier, aimer, commenter, suivre et envoyer un message.
- Lisible sur differents formats d'ecran.

### 7.4 Maintenabilite

Le code doit :

- Respecter l'architecture Laravel.
- Separer les responsabilites entre controleurs, modeles et services.
- Utiliser des modeles Eloquent pour representer les entites metier.
- Centraliser la logique de gamification dans des services dedies.

### 7.5 Fiabilite

Le systeme doit :

- Retourner des erreurs claires lorsque les donnees sont invalides.
- Empecher les actions non autorisees.
- Conserver la coherence des compteurs de likes et commentaires.
- Gerer les erreurs de diffusion des messages sans bloquer l'envoi.

## 8. Contraintes techniques

Le projet est developpe avec les technologies suivantes :

- Framework backend : Laravel.
- Langage backend : PHP.
- Base de donnees relationnelle compatible Laravel.
- Frontend : Blade, Livewire, JavaScript, CSS et Tailwind/Vite.
- Authentification : systeme Laravel.
- Stockage des medias : disque public Laravel.
- Architecture : MVC avec services metier.

## 9. Regles de gestion

- Un utilisateur doit etre connecte pour publier, aimer, commenter, envoyer un message ou consulter ses taches.
- Un utilisateur ne peut supprimer que ses propres publications.
- Un utilisateur ne peut pas se suivre lui-meme.
- Un utilisateur ne peut pas ouvrir une conversation avec lui-meme.
- Un utilisateur ne peut consulter que les conversations auxquelles il participe.
- Une liste d'audience ne peut etre modifiee que par son proprietaire.
- Les taches quotidiennes sont attribuees par jour.
- Certaines taches peuvent etre completees automatiquement selon les actions realisees.
- L'XP est attribuee lorsqu'une tache est completee.
- Les badges sont attribues selon des criteres definis, notamment le niveau d'XP.
- L'administration est reservee aux utilisateurs ayant le role administrateur.

## 10. Donnees principales

Les principales entites manipulees par le systeme sont :

- User : utilisateur de la plateforme.
- Post : publication.
- PostMedia : media associe a une publication.
- Comment : commentaire.
- Like : j'aime.
- Follow : relation d'abonnement.
- Uplift : interaction positive entre utilisateurs.
- Notification : notification utilisateur.
- Conversation : discussion entre deux utilisateurs.
- Message : message d'une conversation.
- Game : jeu associe aux utilisateurs.
- DailyTask : tache quotidienne.
- UserDailyTask : tache assignee a un utilisateur.
- Badge : recompense.
- BadgeUser : association entre utilisateur et badge.
- AudienceList : liste d'audience.
- AudienceListMember : membre d'une liste d'audience.

## 11. Scenarios principaux

### 11.1 Creation d'une publication

1. L'utilisateur se connecte.
2. Il saisit le contenu de sa publication.
3. Il ajoute eventuellement un ou plusieurs medias.
4. Il choisit la confidentialite.
5. Le systeme valide les donnees.
6. Le post est cree.
7. Les medias sont stockes et associes au post.
8. Le systeme verifie les taches quotidiennes liees a l'action de publier.
9. Un message de succes est affiche.

### 11.2 Envoi d'un message

1. L'utilisateur ouvre une conversation.
2. Il saisit un message.
3. Le systeme verifie que l'utilisateur appartient a la conversation.
4. Le message est valide.
5. Le message est enregistre.
6. La conversation est mise a jour.
7. L'evenement de message est diffuse.
8. Le message cree est retourne a l'interface.

### 11.3 Completion d'une tache quotidienne

1. L'utilisateur consulte la page des taches.
2. Le systeme cherche les taches du jour.
3. Si aucune tache n'existe, cinq taches sont assignees.
4. L'utilisateur realise une action ou coche une tache.
5. Le systeme met a jour l'etat de la tache.
6. L'XP de l'utilisateur est mis a jour.
7. Le systeme verifie si un badge peut etre attribue.

## 12. Livrables attendus

Les livrables du projet sont :

- Application web Nexus fonctionnelle.
- Base de donnees avec les tables necessaires.
- Interfaces utilisateur principales.
- Interface d'administration.
- Diagrammes UML : cas d'utilisation, sequences et classes.
- Cahier des charges.
- Documentation de presentation du projet.

## 13. Conclusion

Nexus est une plateforme sociale gamifiee qui combine publication de contenu, interactions sociales, messagerie, gestion de profil et progression utilisateur. Le projet met l'accent sur l'engagement des membres grace aux taches quotidiennes, a l'XP et aux badges. Son architecture Laravel permet une organisation claire du code et facilite l'evolution future de la plateforme.

