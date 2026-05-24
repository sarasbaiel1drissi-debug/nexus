Add-Type -AssemblyName System.IO.Compression
Add-Type -AssemblyName System.IO.Compression.FileSystem

$ErrorActionPreference = 'Stop'

$outputPath = Join-Path (Get-Location) 'Rapport de PFA - Nexus.docx'
if (Test-Path $outputPath) {
    Remove-Item -LiteralPath $outputPath -Force
}

function XmlEscape([string]$text) {
    if ($null -eq $text) { return '' }
    return [System.Security.SecurityElement]::Escape($text)
}

function Para([string]$text, [string]$style = 'Normal') {
    $escaped = XmlEscape $text
    $styleXml = ''
    if ($style -and $style -ne 'Normal') {
        $styleXml = "<w:pPr><w:pStyle w:val=`"$style`"/></w:pPr>"
    }
    return "<w:p>$styleXml<w:r><w:t xml:space=`"preserve`">$escaped</w:t></w:r></w:p>"
}

function Bullet([string]$text) {
    $escaped = XmlEscape $text
    return "<w:p><w:pPr><w:pStyle w:val=`"ListParagraph`"/><w:numPr><w:ilvl w:val=`"0`"/><w:numId w:val=`"1`"/></w:numPr></w:pPr><w:r><w:t xml:space=`"preserve`">$escaped</w:t></w:r></w:p>"
}

function PageBreak() {
    return '<w:p><w:r><w:br w:type="page"/></w:r></w:p>'
}

$body = New-Object System.Collections.Generic.List[string]

$body.Add((Para 'INSTITUT DE MANAGEMENT & BUSINESS TECHNOLOGY' 'Title'))
$body.Add((Para 'RABAT' 'Subtitle'))
$body.Add((Para 'Master 1 en Développement Informatique' 'Subtitle'))
$body.Add((Para 'Rapport de projet' 'Heading1'))
$body.Add((Para 'Projet de fin d''année : conception et développement d''une plateforme Web sociale dédiée aux joueurs intitulée NEXUS' 'Heading1'))
$body.Add((Para 'Réalisé par :'))
$body.Add((Para 'SBAI EL IDRISSI Sara'))
$body.Add((Para 'Encadré par :'))
$body.Add((Para 'Mr. BENBOUBKER Mohamed Amine'))
$body.Add((PageBreak))

$body.Add((Para 'Remerciements' 'Heading1'))
$body.Add((Para 'Je tiens à exprimer ma profonde gratitude à toutes les personnes qui ont contribué, de près ou de loin, à la réalisation de ce projet.'))
$body.Add((Para 'Je remercie tout particulièrement mes encadrants pour leurs conseils, leurs orientations et leurs remarques constructives tout au long de ce travail. Leur accompagnement m''a permis de mieux structurer l''analyse, de faire des choix techniques cohérents et d''améliorer progressivement la qualité de l''application.'))
$body.Add((Para 'Je remercie également la direction de l''établissement ainsi que l''ensemble de l''équipe pédagogique pour les moyens mis à notre disposition et pour leur engagement dans notre formation.'))
$body.Add((Para 'Enfin, je remercie mes camarades pour les échanges, la motivation et l''ambiance de travail partagée. Ce projet n''aurait pas pu aboutir sans ce cadre d''apprentissage, d''entraide et de persévérance.'))
$body.Add((PageBreak))

$body.Add((Para 'Sommaire' 'Heading1'))
@(
    'Remerciements',
    'Glossaire',
    'Introduction générale',
    'Présentation du projet',
    'Cahier des charges - Projet Nexus',
    'Analyse et conception du projet',
    'Architecture générale',
    'Technologies utilisées',
    'Conception UML',
    'Réalisation du projet : l''application Web Nexus',
    'Conclusion et perspectives',
    'Table de figures',
    'Références'
) | ForEach-Object { $body.Add((Bullet $_)) }
$body.Add((PageBreak))

$body.Add((Para 'Glossaire' 'Heading1'))
$glossary = @(
    @('Hashing', 'Le hachage est une méthode de transformation irréversible utilisée notamment pour sécuriser les mots de passe. Dans Nexus, les mots de passe sont traités par le mécanisme de hash intégré à Laravel.'),
    @('CSRF', 'Le Cross-Site Request Forgery est une attaque qui pousse un utilisateur authentifié à exécuter une action non souhaitée. Laravel protège les formulaires grâce à des jetons CSRF.'),
    @('Responsive', 'Une interface responsive s''adapte automatiquement aux différentes tailles d''écran afin de garantir une expérience fluide sur ordinateur, tablette et mobile.'),
    @('CRUD', 'CRUD désigne les opérations de base sur les données : créer, lire, mettre à jour et supprimer. Nexus utilise ce principe dans le backoffice et dans plusieurs modules fonctionnels.'),
    @('ORM', 'Un ORM permet de manipuler la base de données à travers des objets. Nexus utilise Eloquent, l''ORM de Laravel, pour gérer ses modèles et relations.'),
    @('SSE', 'Server-Sent Events est une technique permettant au serveur d''envoyer des mises à jour en continu vers le navigateur. Nexus l''utilise pour le flux de messages.'),
    @('Livewire', 'Livewire est une technologie Laravel permettant de créer des composants interactifs côté serveur, sans écrire une grande quantité de JavaScript.'),
    @('MVC', 'Modèle-Vue-Contrôleur est une architecture qui sépare les données, l''interface utilisateur et la logique de contrôle.')
)
foreach ($item in $glossary) {
    $body.Add((Para ($item[0] + ' :') 'Heading2'))
    $body.Add((Para $item[1]))
}
$body.Add((PageBreak))

$body.Add((Para 'Introduction générale' 'Heading1'))
$body.Add((Para 'Le jeu vidéo occupe aujourd''hui une place importante dans la culture numérique. Il ne se limite plus à un simple divertissement individuel : il est devenu un espace d''échange, de compétition, d''apprentissage et de sociabilité. Les joueurs cherchent de plus en plus à trouver des coéquipiers fiables, à partager leurs expériences, à discuter autour de leurs jeux favoris et à construire une identité numérique propre à leur univers.'))
$body.Add((Para 'Cependant, cette expérience reste souvent fragmentée. Les joueurs utilisent des plateformes séparées pour discuter, publier du contenu, chercher des partenaires de jeu, suivre leur progression ou organiser leurs contacts. Cette dispersion rend l''expérience moins fluide, surtout pour les utilisateurs qui souhaitent centraliser leurs interactions dans un environnement simple, personnalisé et orienté gaming.'))
$body.Add((Para 'C''est dans ce contexte que s''inscrit Nexus, une application web sociale destinée aux joueurs. Le projet vise à proposer un espace où les utilisateurs peuvent créer un profil, publier du contenu, interagir avec les publications, suivre d''autres joueurs, envoyer des messages, gérer leurs jeux, rechercher des partenaires selon différents critères et progresser grâce à des badges et des tâches quotidiennes.'))
$body.Add((Para 'Nexus se présente donc comme un réseau social thématique, conçu pour rapprocher les joueurs et enrichir leur expérience communautaire. L''application combine des fonctionnalités classiques de réseau social avec des mécanismes spécifiques au monde du gaming, tels que la recherche de joueurs par jeu, rang, rôle préféré, langue ou région.'))

$body.Add((Para 'Présentation du projet' 'Heading1'))
$body.Add((Para 'Concept du projet' 'Heading2'))
$body.Add((Para 'Nexus est une plateforme web développée avec Laravel. Elle permet de créer un écosystème social autour des joueurs, en réunissant profil personnel, fil d''actualité, messagerie, recherche de partenaires, gestion des jeux, notifications, badges et tâches quotidiennes.'))
$body.Add((Para 'L''objectif principal est de faciliter la connexion entre joueurs ayant des intérêts, niveaux ou disponibilités compatibles. Chaque utilisateur peut enrichir son profil avec des informations gaming comme ses jeux, son rang, son rôle préféré, ses langues, sa région, sa disponibilité et ses préférences.'))
$body.Add((Para 'Pourquoi ce projet ?' 'Heading2'))
$body.Add((Para 'Les communautés gaming sont très actives, mais elles restent souvent dispersées entre différents outils. Nexus répond à ce besoin en proposant une interface unique, centrée sur la mise en relation et l''interaction sociale. Le projet cherche à rendre l''expérience plus directe : trouver un joueur, consulter son profil, le suivre, lui envoyer un message et interagir avec ses publications.'))
$body.Add((Para 'La cible et la valeur ajoutée' 'Heading2'))
$body.Add((Para 'Le public cible est composé principalement de joueurs, débutants ou expérimentés, souhaitant élargir leur réseau, former des équipes ou partager du contenu autour de leurs jeux. La valeur ajoutée de Nexus réside dans son orientation gaming, sa simplicité d''utilisation et l''intégration de modules sociaux dans un même environnement.'))

$body.Add((Para 'Cahier des Charges - Projet Nexus' 'Heading1'))
$body.Add((Para 'Le cahier des charges définit les besoins fonctionnels et techniques de l''application Nexus. Il précise les modules attendus, les contraintes de développement, les exigences de sécurité et les livrables du projet.'))
$body.Add((Para 'Fonctionnalités principales' 'Heading2'))
@(
    'Authentification sécurisée : inscription, connexion, déconnexion, vérification email, réinitialisation et mise à jour du mot de passe.',
    'Profil utilisateur : informations personnelles, avatar, bannière, biographie, localisation, couleurs de thème, image de fond et statut en ligne.',
    'Fil d''actualité : affichage paginé des publications visibles selon leur confidentialité.',
    'Publications : création de posts avec texte, images ou vidéos, suppression par l''auteur, confidentialité publique ou abonnés.',
    'Interactions : likes, commentaires, compteur d''interactions et notifications associées.',
    'Système social : suivre/ne plus suivre un utilisateur, uplift, listes d''audience personnalisées et page de connexions.',
    'Recherche de joueurs : filtrage par jeu, rang, rôle préféré, langue et région.',
    'Gestion des jeux : ajout de jeux au profil, création d''un nouveau jeu, modification du rang et du rôle préféré.',
    'Messagerie : conversations privées, messages lus/non lus, ouverture directe d''une discussion et flux temps réel.',
    'Badges et tâches quotidiennes : attribution de missions, progression, XP et récompenses.',
    'Backoffice administratif : gestion des utilisateurs, badges et tables principales via interface protégée.',
    'Notifications : suivi des actions importantes comme follow, uplift ou interactions sur les publications.'
) | ForEach-Object { $body.Add((Bullet $_)) }
$body.Add((Para 'Pages principales' 'Heading2'))
@(
    'Page de connexion : point d''entrée de l''application.',
    'Dashboard : fil d''actualité et publications récentes.',
    'Profil : consultation et personnalisation de l''identité utilisateur.',
    'Find Players : recherche avancée de joueurs selon des critères gaming.',
    'Messages : interface de conversations privées.',
    'Notifications : historique des notifications utilisateur.',
    'Connections : followers, following, mutuals et listes d''audience.',
    'Tasks : tâches quotidiennes et progression XP.',
    'Admin : gestion protégée des ressources.'
) | ForEach-Object { $body.Add((Bullet $_)) }
$body.Add((Para 'Contraintes' 'Heading2'))
@(
    'Sécurité : accès authentifié aux modules privés, contrôle des autorisations et protection CSRF.',
    'Performance : pagination du fil d''actualité et des recherches pour éviter des chargements lourds.',
    'Ergonomie : interface claire, responsive et adaptée à un usage fréquent.',
    'Maintenabilité : séparation MVC, modèles Eloquent, contrôleurs dédiés et composants Livewire.',
    'Évolutivité : possibilité d''ajouter de nouveaux modules gaming, tournois ou événements.'
) | ForEach-Object { $body.Add((Bullet $_)) }
$body.Add((Para 'Livrables attendus' 'Heading2'))
@(
    'Application web fonctionnelle et sécurisée.',
    'Base de données relationnelle structurée.',
    'Interfaces utilisateur pour les joueurs et interface d''administration.',
    'Modules sociaux, gaming et messagerie opérationnels.',
    'Documentation de conception et de réalisation.'
) | ForEach-Object { $body.Add((Bullet $_)) }

$body.Add((Para 'Analyse et conception du projet' 'Heading1'))
$body.Add((Para 'Analyse du projet' 'Heading2'))
$body.Add((Para 'L''analyse du projet Nexus repose sur la compréhension des besoins d''une communauté de joueurs. Un utilisateur doit pouvoir se présenter, publier, interagir, trouver des joueurs compatibles et communiquer avec eux. L''application doit également encourager l''activité à travers des tâches quotidiennes, de l''XP et des badges.'))
$body.Add((Para 'Objectifs fonctionnels' 'Heading2'))
@(
    'Créer un espace social centré sur les joueurs.',
    'Permettre la découverte de profils compatibles selon des critères gaming.',
    'Favoriser les interactions par les follows, uplifts, likes et commentaires.',
    'Centraliser les conversations privées.',
    'Donner à l''administrateur des outils de gestion simples.',
    'Gamifier l''activité grâce aux badges, XP et tâches journalières.'
) | ForEach-Object { $body.Add((Bullet $_)) }
$body.Add((Para 'Objectifs techniques' 'Heading2'))
@(
    'Utiliser Laravel comme framework principal pour l''architecture MVC.',
    'Utiliser Eloquent pour gérer les relations entre utilisateurs, posts, jeux, messages et badges.',
    'Utiliser Livewire pour les composants interactifs comme la recherche de joueurs, la messagerie et la gestion des jeux.',
    'Sécuriser les accès avec les middlewares auth, verified et admin.',
    'Valider les formulaires et limiter les fichiers uploadés par type et taille.',
    'Gérer les messages en temps réel grâce au streaming et aux événements.'
) | ForEach-Object { $body.Add((Bullet $_)) }

$body.Add((Para 'Architecture générale' 'Heading1'))
$body.Add((Para 'Le projet adopte une architecture MVC. Les modèles représentent les entités de la base de données, les vues Blade constituent l''interface utilisateur et les contrôleurs assurent la communication entre les deux. Les composants Livewire ajoutent une couche interactive tout en restant intégrés à Laravel.'))
$body.Add((Para 'Les routes sont centralisées dans web.php. Les routes publiques concernent l''accueil et les profils publics, tandis que les fonctionnalités principales sont protégées par le middleware auth. Les routes administratives sont protégées par un préfixe admin et par le middleware admin.'))
$body.Add((Para 'Technologies utilisées' 'Heading1'))
$body.Add((Para 'Frontend' 'Heading2'))
@(
    'Blade : moteur de templates Laravel.',
    'HTML5 et CSS3 : structure et mise en forme.',
    'Bootstrap, Tailwind CSS et feuilles de style personnalisées : interface responsive.',
    'JavaScript : interactions dynamiques côté client.',
    'Livewire : composants interactifs côté serveur.'
) | ForEach-Object { $body.Add((Bullet $_)) }
$body.Add((Para 'Backend' 'Heading2'))
@(
    'Laravel 12 : framework PHP principal.',
    'PHP 8.2 : langage serveur.',
    'Laravel Breeze : base d''authentification.',
    'Laravel Reverb et événements : communication temps réel.',
    'Eloquent ORM : manipulation des données relationnelles.'
) | ForEach-Object { $body.Add((Bullet $_)) }
$body.Add((Para 'Base de données' 'Heading2'))
@(
    'MySQL : base relationnelle utilisée avec Laravel.',
    'Migrations : création et évolution de la structure des tables.',
    'phpMyAdmin : administration locale de la base de données.'
) | ForEach-Object { $body.Add((Bullet $_)) }

$body.Add((Para 'Conception du projet (UML)' 'Heading1'))
$body.Add((Para 'Diagramme de cas d''utilisation' 'Heading2'))
$body.Add((Para 'Figure 1. Diagramme de cas d''utilisation : système social gaming Nexus'))
$body.Add((Para 'Le système distingue deux acteurs principaux : l''utilisateur authentifié et l''administrateur. L''utilisateur peut gérer son profil, publier du contenu, interagir avec les publications, suivre d''autres joueurs, envoyer des messages, consulter ses notifications, gérer ses jeux et réaliser des tâches quotidiennes. L''administrateur peut accéder au backoffice, gérer les utilisateurs, les badges et les données principales de l''application.'))
$body.Add((Para 'Diagramme de séquence : recherche de joueurs' 'Heading2'))
$body.Add((Para 'Figure 2. Processus de recherche de joueurs'))
@(
    'L''utilisateur ouvre la page Find Players.',
    'Le composant Livewire PlayerFinder charge les jeux disponibles.',
    'L''utilisateur renseigne les filtres : jeu, rang, rôle, langue ou région.',
    'Le composant construit une requête Eloquent avec les relations games.',
    'Les résultats sont paginés et triés selon le nombre d''uplifts reçus.',
    'L''utilisateur peut consulter un profil ou initier une interaction sociale.'
) | ForEach-Object { $body.Add((Bullet $_)) }
$body.Add((Para 'Diagramme de séquence : messagerie privée' 'Heading2'))
$body.Add((Para 'Figure 3. Processus d''envoi d''un message'))
@(
    'L''utilisateur ouvre une conversation avec un autre joueur.',
    'Le système vérifie si une conversation existe déjà.',
    'Si nécessaire, une nouvelle conversation est créée.',
    'Le message est validé puis enregistré dans la table messages.',
    'La conversation est mise à jour et les messages non lus sont recalculés.',
    'Un événement peut être diffusé et le flux SSE permet de recevoir les nouveaux messages.'
) | ForEach-Object { $body.Add((Bullet $_)) }
$body.Add((Para 'Diagramme de classes' 'Heading2'))
$body.Add((Para 'Figure 4. Diagramme de classe du projet Nexus'))
$body.Add((Para 'Les principales entités du système sont User, Post, PostMedia, Like, Comment, Game, Conversation, Message, Follow, Uplift, Notification, Badge, DailyTask, UserDailyTask, AudienceList et AudienceListMember. L''utilisateur est au centre du modèle : il possède des posts, des commentaires, des likes, des conversations, des listes d''audience, des badges et des tâches quotidiennes.'))
$body.Add((Para 'La relation entre User et Game est de type plusieurs-à-plusieurs via la table game_user. Cette table ajoute des informations spécifiques au profil gaming comme le rang et le rôle préféré. Les conversations relient deux utilisateurs, tandis que les messages appartiennent à une conversation et à un expéditeur.'))

$body.Add((Para 'Réalisation du projet : L''application Web Nexus' 'Heading1'))
$body.Add((Para 'Authentification' 'Heading2'))
$body.Add((Para 'L''application propose un système d''authentification complet basé sur Laravel Breeze. Les utilisateurs peuvent s''inscrire, se connecter, vérifier leur adresse email, réinitialiser leur mot de passe et mettre à jour leurs informations de sécurité. Les mots de passe sont automatiquement hachés et les sessions sont gérées par Laravel.'))
$body.Add((Para 'Dashboard et fil d''actualité' 'Heading2'))
$body.Add((Para 'Le dashboard constitue l''espace principal après connexion. Il affiche les publications les plus récentes, chargées avec leurs relations : auteur, médias, likes et commentaires. La visibilité des publications est contrôlée par un scope visibleTo, ce qui permet de distinguer les publications publiques et celles limitées aux abonnés.'))
$body.Add((Para 'Création de publications' 'Heading2'))
$body.Add((Para 'Un utilisateur peut publier du texte et ajouter des médias. Les fichiers acceptés incluent les images et vidéos courantes, avec une limite de taille. Les médias sont stockés dans le disque public et enregistrés dans la table post_media avec leur type, leur taille, leur ordre et des métadonnées.'))
$body.Add((Para 'Interactions sociales' 'Heading2'))
$body.Add((Para 'Les utilisateurs peuvent aimer ou commenter une publication. Les compteurs sont mis à jour afin d''afficher rapidement le nombre de likes et de commentaires. Les actions sociales peuvent également déclencher l''avancement de tâches quotidiennes.'))
$body.Add((Para 'Profils utilisateurs' 'Heading2'))
$body.Add((Para 'Chaque utilisateur dispose d''un profil personnalisable contenant son nom, son username, sa biographie, sa localisation, son avatar, sa couverture, son thème visuel, son statut en ligne, ses informations gaming et ses statistiques sociales. Un profil public est accessible via une URL basée sur le username.'))
$body.Add((Para 'Recherche de joueurs' 'Heading2'))
$body.Add((Para 'La page Find Players permet d''identifier des joueurs selon différents critères : jeu, rang, rôle préféré, langue et région. Les résultats sont paginés et les utilisateurs les plus valorisés par la communauté peuvent remonter grâce au nombre d''uplifts reçus.'))
$body.Add((Para 'Gestion des jeux' 'Heading2'))
$body.Add((Para 'Le composant GameManager permet à l''utilisateur d''ajouter des jeux à son profil, de créer un jeu s''il n''existe pas encore, puis d''indiquer son rang et son rôle préféré. Ces informations enrichissent le matching entre joueurs.'))
$body.Add((Para 'Messagerie' 'Heading2'))
$body.Add((Para 'La messagerie permet d''ouvrir ou de créer une conversation privée entre deux utilisateurs. Les messages sont associés à une conversation, à un expéditeur et à une date de lecture. Le système calcule les messages non lus et marque automatiquement les messages comme lus lors de la consultation.'))
$body.Add((Para 'Notifications' 'Heading2'))
$body.Add((Para 'Les notifications centralisent les événements importants, comme un follow, un uplift ou une interaction liée à une publication. Elles sont affichées dans une page dédiée puis marquées comme lues après consultation.'))
$body.Add((Para 'Connexions et listes d''audience' 'Heading2'))
$body.Add((Para 'La page Connections présente les abonnements, les abonnés et les relations mutuelles. L''utilisateur peut également créer des listes personnalisées d''audience et y ajouter ou retirer des membres. Ce module prépare une gestion plus fine de la confidentialité et des groupes sociaux.'))
$body.Add((Para 'Badges, XP et tâches quotidiennes' 'Heading2'))
$body.Add((Para 'Nexus intègre un système de gamification. Des tâches quotidiennes sont assignées aux utilisateurs. Certaines peuvent être complétées manuellement, tandis que d''autres se valident automatiquement après une action, par exemple publier, aimer, commenter, envoyer un message ou suivre un utilisateur. La progression peut accorder de l''XP et des badges.'))
$body.Add((Para 'Backoffice administratif' 'Heading2'))
$body.Add((Para 'L''espace administrateur est protégé par un middleware dédié. Il permet de gérer les utilisateurs, les badges et différentes tables du système. Ce backoffice facilite la supervision de la plateforme et la maintenance des données.'))
$body.Add((Para 'Responsive' 'Heading2'))
$body.Add((Para 'L''interface de Nexus utilise des feuilles de style modernes et des composants adaptés aux écrans variés. Les pages principales sont pensées pour rester lisibles et utilisables sur desktop comme sur mobile.'))

$body.Add((Para 'Conclusion et perspectives' 'Heading1'))
$body.Add((Para 'La réalisation de Nexus a permis de concevoir une application web sociale orientée gaming, combinant les mécanismes essentiels d''un réseau social avec des besoins propres aux joueurs. Le projet centralise le profil, les publications, les interactions, la messagerie, la recherche de partenaires, les badges et les tâches quotidiennes dans une même plateforme.'))
$body.Add((Para 'Sur le plan technique, l''utilisation de Laravel, Eloquent, Blade et Livewire a permis de construire une base claire, maintenable et évolutive. L''architecture MVC facilite la séparation des responsabilités, tandis que les composants interactifs améliorent l''expérience utilisateur sans complexifier excessivement le frontend.'))
$body.Add((Para 'Plusieurs perspectives peuvent être envisagées pour enrichir Nexus :'))
@(
    'Développer une application mobile native ou hybride.',
    'Ajouter un module de tournois et d''événements communautaires.',
    'Intégrer un système de matchmaking plus avancé basé sur la disponibilité et le style de jeu.',
    'Ajouter des appels vocaux ou salons de groupe.',
    'Améliorer les statistiques de profil et les classements.',
    'Renforcer la modération avec signalement de contenu et gestion des sanctions.',
    'Déployer l''application en production avec une configuration temps réel complète.'
) | ForEach-Object { $body.Add((Bullet $_)) }
$body.Add((Para 'En conclusion, Nexus constitue une base solide pour un écosystème digital dédié aux joueurs. Le projet peut évoluer progressivement vers une plateforme communautaire plus complète, capable de réunir interaction sociale, progression personnelle et organisation gaming.'))

$body.Add((Para 'Table de figures' 'Heading1'))
@(
    'Figure 1. Diagramme de cas d''utilisation : système social gaming Nexus',
    'Figure 2. Processus de recherche de joueurs',
    'Figure 3. Processus d''envoi d''un message',
    'Figure 4. Diagramme de classe du projet Nexus',
    'Figure 5. Page de connexion Nexus',
    'Figure 6. Dashboard et fil d''actualité',
    'Figure 7. Profil utilisateur',
    'Figure 8. Page Find Players',
    'Figure 9. Interface de messagerie',
    'Figure 10. Page des tâches quotidiennes',
    'Figure 11. Backoffice administrateur'
) | ForEach-Object { $body.Add((Bullet $_)) }

$body.Add((Para 'Références' 'Heading1'))
@(
    'Documentation Laravel : https://laravel.com/docs',
    'Documentation Livewire : https://livewire.laravel.com/docs',
    'Documentation PHP : https://www.php.net/docs.php',
    'Documentation MySQL : https://dev.mysql.com/doc/',
    'OWASP - Cross-Site Request Forgery : https://owasp.org/www-community/attacks/csrf',
    'Object-Relational Mapping : https://en.wikipedia.org/wiki/Object%E2%80%93relational_mapping',
    'Responsive Web Design : https://www.nngroup.com/articles/responsive-web-design-definition/',
    'Unified Modeling Language : https://www.uml.org/'
) | ForEach-Object { $body.Add((Para $_)) }

$sectPr = '<w:sectPr><w:pgSz w:w="11906" w:h="16838"/><w:pgMar w:top="1440" w:right="1440" w:bottom="1440" w:left="1440" w:header="720" w:footer="720" w:gutter="0"/></w:sectPr>'
$documentXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' +
    '<w:document xmlns:wpc="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:wp14="http://schemas.microsoft.com/office/word/2010/wordprocessingDrawing" xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing" xmlns:w10="urn:schemas-microsoft-com:office:word" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml" xmlns:wpg="http://schemas.microsoft.com/office/word/2010/wordprocessingGroup" xmlns:wpi="http://schemas.microsoft.com/office/word/2010/wordprocessingInk" xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml" xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape" mc:Ignorable="w14 wp14"><w:body>' +
    ($body -join '') + $sectPr + '</w:body></w:document>'

$stylesXml = @'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:style w:type="paragraph" w:default="1" w:styleId="Normal"><w:name w:val="Normal"/><w:qFormat/><w:pPr><w:spacing w:after="160" w:line="276" w:lineRule="auto"/></w:pPr><w:rPr><w:rFonts w:ascii="Calibri" w:hAnsi="Calibri"/><w:sz w:val="22"/></w:rPr></w:style>
  <w:style w:type="paragraph" w:styleId="Title"><w:name w:val="Title"/><w:basedOn w:val="Normal"/><w:qFormat/><w:pPr><w:jc w:val="center"/><w:spacing w:after="240"/></w:pPr><w:rPr><w:b/><w:sz w:val="36"/></w:rPr></w:style>
  <w:style w:type="paragraph" w:styleId="Subtitle"><w:name w:val="Subtitle"/><w:basedOn w:val="Normal"/><w:qFormat/><w:pPr><w:jc w:val="center"/></w:pPr><w:rPr><w:i/><w:sz w:val="26"/></w:rPr></w:style>
  <w:style w:type="paragraph" w:styleId="Heading1"><w:name w:val="heading 1"/><w:basedOn w:val="Normal"/><w:next w:val="Normal"/><w:qFormat/><w:pPr><w:keepNext/><w:spacing w:before="360" w:after="200"/></w:pPr><w:rPr><w:b/><w:color w:val="1F4E79"/><w:sz w:val="30"/></w:rPr></w:style>
  <w:style w:type="paragraph" w:styleId="Heading2"><w:name w:val="heading 2"/><w:basedOn w:val="Normal"/><w:next w:val="Normal"/><w:qFormat/><w:pPr><w:keepNext/><w:spacing w:before="240" w:after="120"/></w:pPr><w:rPr><w:b/><w:color w:val="2F5597"/><w:sz w:val="26"/></w:rPr></w:style>
  <w:style w:type="paragraph" w:styleId="ListParagraph"><w:name w:val="List Paragraph"/><w:basedOn w:val="Normal"/><w:pPr><w:ind w:left="720"/></w:pPr></w:style>
</w:styles>
'@

$numberingXml = @'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:numbering xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:abstractNum w:abstractNumId="0">
    <w:multiLevelType w:val="hybridMultilevel"/>
    <w:lvl w:ilvl="0">
      <w:start w:val="1"/>
      <w:numFmt w:val="bullet"/>
      <w:lvlText w:val="•"/>
      <w:lvlJc w:val="left"/>
      <w:pPr><w:ind w:left="720" w:hanging="360"/></w:pPr>
      <w:rPr><w:rFonts w:ascii="Symbol" w:hAnsi="Symbol" w:hint="default"/></w:rPr>
    </w:lvl>
  </w:abstractNum>
  <w:num w:numId="1"><w:abstractNumId w:val="0"/></w:num>
</w:numbering>
'@

$contentTypesXml = @'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
  <Override PartName="/word/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>
  <Override PartName="/word/numbering.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.numbering+xml"/>
</Types>
'@

$rootRelsXml = @'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
</Relationships>
'@

$documentRelsXml = @'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/numbering" Target="numbering.xml"/>
</Relationships>
'@

function Add-ZipEntry($zip, [string]$name, [string]$content) {
    $entry = $zip.CreateEntry($name)
    $stream = $entry.Open()
    $writer = New-Object System.IO.StreamWriter($stream, [System.Text.Encoding]::UTF8)
    $writer.Write($content)
    $writer.Dispose()
}

$fs = [System.IO.File]::Open($outputPath, [System.IO.FileMode]::CreateNew)
$zip = New-Object System.IO.Compression.ZipArchive($fs, [System.IO.Compression.ZipArchiveMode]::Create)
Add-ZipEntry $zip '[Content_Types].xml' $contentTypesXml
Add-ZipEntry $zip '_rels/.rels' $rootRelsXml
Add-ZipEntry $zip 'word/document.xml' $documentXml
Add-ZipEntry $zip 'word/_rels/document.xml.rels' $documentRelsXml
Add-ZipEntry $zip 'word/styles.xml' $stylesXml
Add-ZipEntry $zip 'word/numbering.xml' $numberingXml
$zip.Dispose()
$fs.Dispose()

Write-Output $outputPath
