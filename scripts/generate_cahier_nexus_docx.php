<?php

$output = __DIR__ . '/../docs/Cahier_des_Charges_Nexus.docx';

$sections = [
    [
        'heading' => 'Cahier des Charges - Projet Nexus',
        'paragraphs' => [
            'Nexus est une application web sociale orientee gaming, concue pour favoriser les interactions entre joueurs. Elle permet aux utilisateurs de creer un profil personnalise, publier du contenu, interagir avec les autres membres, echanger des messages prives, suivre des joueurs, recevoir des notifications et accomplir des taches quotidiennes afin de gagner de l XP et des badges.',
            'Le projet vise a proposer une plateforme centralisee ou chaque utilisateur peut construire son identite numerique de joueur, partager ses activites, decouvrir d autres profils et renforcer son engagement grace a un systeme de gamification.',
        ],
    ],
    [
        'heading' => 'Objectifs du Projet',
        'paragraphs' => [
            'L objectif principal de Nexus est de creer un espace communautaire moderne dedie aux joueurs. La plateforme doit faciliter la mise en relation entre utilisateurs selon leurs jeux, leurs preferences, leur region, leur langue, leur rang ou leur role prefere.',
            'Le projet a egalement pour objectif d encourager l activite reguliere des membres grace a des mecanismes motivants comme les taches quotidiennes, les points d experience, les badges et les uplifts.',
        ],
    ],
    [
        'heading' => 'Perimetre Fonctionnel',
        'bullets' => [
            'Authentification securisee : inscription, connexion, deconnexion, reinitialisation du mot de passe et mise a jour des informations d acces avec Laravel Breeze.',
            'Profil utilisateur : informations personnelles, avatar, banniere, biographie, localisation, preferences gaming, disponibilite, statut en ligne et personnalisation visuelle du profil.',
            'Fil d actualite : affichage des publications selon leur confidentialite et selon les relations entre utilisateurs.',
            'Publications : creation de posts avec texte, images ou videos, ajout d un ou plusieurs medias, suppression par l auteur et choix de confidentialite entre public et followers only.',
            'Interactions : likes, commentaires, compteurs d interactions et notifications liees aux actions effectuees sur les publications.',
            'Systeme social : suivre ou ne plus suivre un utilisateur, donner un uplift a un profil, gerer des listes d audience personnalisees et consulter la page Connections.',
            'Recherche de joueurs : filtrage avance des profils par jeu, rang, role prefere, langue et region.',
            'Gestion des jeux : ajout de jeux au profil, creation d un nouveau jeu si celui-ci n existe pas, modification du rang et du role prefere, ainsi que suppression d un jeu.',
            'Messagerie privee : conversations entre utilisateurs, messages lus et non lus, ouverture directe d une discussion depuis un profil et flux temps reel.',
            'Badges et taches quotidiennes : attribution de missions journalieres, suivi de la progression, gain d XP et obtention de recompenses.',
            'Notifications : suivi des actions importantes comme les follows, uplifts, likes, commentaires et messages.',
            'Backoffice administratif : interface protegee permettant la gestion des utilisateurs, publications, badges, taches quotidiennes et principales tables de l application.',
        ],
    ],
    [
        'heading' => 'Architecture Technique',
        'paragraphs' => [
            'Nexus est developpe avec Laravel et respecte une organisation basee sur le modele MVC. Les controleurs gerent les requetes, les modeles Eloquent representent les donnees de la base, les vues Blade construisent les interfaces et certains composants Livewire apportent de l interactivite.',
            'Le projet utilise une base de donnees relationnelle structuree autour de plusieurs entites principales : users, posts, post_media, comments, likes, games, game_user, conversations, messages, follows, uplifts, notifications, daily_tasks, user_daily_tasks, badges et badge_user.',
            'Les relations entre les entites permettent de lier les utilisateurs a leurs publications, jeux, messages, abonnements, notifications, badges et taches quotidiennes. Cette structure rend le projet plus clair, maintenable et evolutif.',
        ],
    ],
    [
        'heading' => 'Authentification et Securite',
        'paragraphs' => [
            'L authentification constitue le point d entree de Nexus. Elle permet aux utilisateurs de creer un compte, se connecter, se deconnecter et gerer leur mot de passe. L application s appuie sur Laravel Breeze afin de beneficier d une base fiable et securisee.',
            'Les mots de passe sont haches avant d etre stockes, les formulaires sont proteges contre les attaques CSRF et les pages privees sont accessibles uniquement aux utilisateurs connectes. L espace administrateur est en plus protege par un middleware qui verifie le role admin via le champ is_admin.',
        ],
    ],
    [
        'heading' => 'Gestion du Profil et des Jeux',
        'paragraphs' => [
            'Le profil utilisateur regroupe les informations principales du joueur : nom, username, email, localisation, date de naissance, genre, biographie, region, langues, disponibilite, tags de style de jeu, avatar et image de couverture.',
            'La partie Games permet a l utilisateur d enrichir son profil avec les jeux auxquels il joue. Il peut rechercher un jeu existant, renseigner son rang ou niveau, indiquer son role prefere, puis l ajouter a son profil. Si le jeu n existe pas encore, l application propose de l ajouter.',
            'Chaque jeu associe au profil peut ensuite etre modifie ou supprime. Cette fonctionnalite permet de garder un profil a jour et facilite la recherche de joueurs compatibles.',
        ],
    ],
    [
        'heading' => 'Publications et Medias',
        'paragraphs' => [
            'Les publications permettent aux utilisateurs de partager du texte, des images ou des videos. L utilisateur peut ajouter plusieurs medias, supprimer un media selectionne ou retirer l ensemble des medias en une seule action avant la publication.',
            'Chaque post possede une visibilite. Une publication peut etre publique ou limitee aux abonnes. Le systeme prend donc en compte la confidentialite afin d afficher le contenu aux bonnes personnes.',
        ],
    ],
    [
        'heading' => 'Interactions Sociales et Uplifts',
        'paragraphs' => [
            'Nexus integre plusieurs interactions sociales : likes, commentaires, follows, messages et uplifts. Ces interactions renforcent l engagement des utilisateurs et donnent vie au fil d actualite.',
            'L uplift est une fonctionnalite qui permet de valoriser le profil d un joueur. Lorsqu un utilisateur donne un uplift a un autre profil, le compteur d uplifts augmente. Les profils ayant plus d uplifts peuvent etre mieux valorises dans les listes de joueurs.',
            'Par exemple, si Rei et User 3 possedent chacun 1 uplift, puis que User 3 recoit un nouvel uplift, son total passe a 2 uplifts. Grace a cette augmentation, son profil devient plus valorise et peut apparaitre en premiere position dans la liste.',
        ],
    ],
    [
        'heading' => 'Messagerie et Temps Reel',
        'paragraphs' => [
            'La messagerie permet aux utilisateurs de communiquer directement entre eux. Une conversation peut etre ouverte depuis le profil d un autre joueur ou depuis l interface Messages.',
            'Au backend, chaque message est enregistre avec son expediteur, sa conversation, son contenu et sa date d envoi. Les messages sont ensuite recuperes dans l ordre chronologique afin de conserver l historique des discussions.',
            'Le systeme utilise un flux temps reel afin d actualiser les conversations sans rechargement manuel. Lorsqu un message est envoye, le serveur le sauvegarde puis le rend disponible a l autre utilisateur. Cette logique rend les conversations plus fluides et plus naturelles.',
        ],
    ],
    [
        'heading' => 'Notifications',
        'paragraphs' => [
            'Les notifications informent l utilisateur des actions importantes effectuees sur son compte ou son contenu : nouvel abonne, uplift recu, like, commentaire ou autre interaction.',
            'L utilisateur peut consulter ses notifications de deux manieres. Le modal de notifications, accessible depuis l icone de cloche, affiche rapidement les notifications recentes sans quitter la page courante. La page Notifications affiche quant a elle l historique complet de facon plus detaillee.',
        ],
    ],
    [
        'heading' => 'Taches Quotidiennes, XP et Auto-Tracked',
        'paragraphs' => [
            'Le module Daily Tasks encourage les utilisateurs a rester actifs. Chaque jour, lorsque l utilisateur accede a la page des taches, Nexus verifie s il possede deja des taches pour la date actuelle. Si ce n est pas le cas, le systeme lui assigne automatiquement 5 taches aleatoires depuis la table daily_tasks.',
            'Chaque tache contient un titre, une description, une categorie, une recompense en XP et parfois une regle de completude automatique appelee auto_complete_rule. Les taches assignees a un utilisateur sont stockees dans user_daily_tasks avec l utilisateur, la tache, la date et l etat completed.',
            'Certaines taches sont manuelles, par exemple jouer une partie ou regarder un tutoriel. D autres sont auto-tracked. Cela signifie que le backend detecte automatiquement certaines actions effectuees dans Nexus.',
            'Le service TaskAutoCompleteService verifie les actions comme message, follow, uplift, comment, like, post et login. Une regle comme message:1 valide la tache apres l envoi d un message. Une regle comme follow:3 valide la tache apres trois nouveaux follows dans la journee.',
            'Lorsque la condition est atteinte, la tache est marquee comme terminee et l XP correspondant est ajoute au compte de l utilisateur. Le systeme verifie ensuite si l XP obtenu permet de debloquer un badge.',
            'Une tache speciale, Complete all daily tasks, utilise la regle all_others_done. Elle ne peut pas etre cochee manuellement et se valide automatiquement lorsque toutes les autres taches du jour sont terminees.',
        ],
    ],
    [
        'heading' => 'Badges et Progression',
        'paragraphs' => [
            'Le systeme de badges renforce la gamification de Nexus. Un badge peut etre attribue automatiquement a un nouvel utilisateur ou lorsqu un seuil d XP est atteint.',
            'Le service BadgeService verifie les criteres des badges et attribue les recompenses lorsque l utilisateur remplit les conditions. Ce mecanisme encourage la progression et valorise l engagement sur la plateforme.',
        ],
    ],
    [
        'heading' => 'Dashboard Administrateur',
        'paragraphs' => [
            'Nexus dispose d un dashboard administrateur protege. Seuls les utilisateurs authentifies ayant le role admin peuvent y acceder. Le middleware admin bloque l acces aux utilisateurs non autorises.',
            'Le dashboard affiche des statistiques principales comme le nombre d utilisateurs, de publications, de badges et de taches quotidiennes. Il affiche egalement les derniers utilisateurs inscrits avec leur nom, username, XP et date d inscription.',
            'L interface admin permet aussi de consulter les tables de la base de donnees et le nombre d enregistrements dans chacune. L administrateur peut gerer plusieurs ressources : users, posts, post_media, comments, likes, games, conversations, messages, follows, uplifts, notifications, daily_tasks, user_daily_tasks, badges et listes d audience.',
            'Pour chaque table, l administrateur peut afficher, creer, modifier ou supprimer des enregistrements. Les formulaires sont generes selon les types de donnees : champs booleens, dates, nombres, JSON, texte long et mots de passe. Les mots de passe sont haches automatiquement avant l enregistrement.',
        ],
    ],
    [
        'heading' => 'Pages Principales',
        'bullets' => [
            'Page de connexion et d inscription : point d entree de l application.',
            'Page d accueil : fil d actualite, publications recentes et creation de posts.',
            'Profil utilisateur : consultation et personnalisation de l identite du joueur.',
            'Find Players : recherche avancee de joueurs selon des criteres gaming.',
            'Messages : interface de conversations privees.',
            'Notifications : historique des notifications utilisateur.',
            'Connections : followers, following, amis mutuels et listes d audience.',
            'Tasks : taches quotidiennes, progression et XP.',
            'Admin : espace d administration protege.',
        ],
    ],
    [
        'heading' => 'Contraintes',
        'bullets' => [
            'Securite : acces authentifie aux modules prives, controle des autorisations, protection CSRF, hachage des mots de passe et limitation des acces administrateur.',
            'Performance : pagination du fil d actualite, des recherches et des tables admin afin d eviter les chargements lourds.',
            'Ergonomie : interface claire, responsive, moderne et adaptee a un usage frequent.',
            'Maintenabilite : separation MVC, modeles Eloquent, controleurs dedies, services metiers et composants Livewire.',
            'Evolutivite : possibilite d ajouter de nouveaux modules comme les tournois, les clans, les evenements gaming, les recommandations automatiques ou une version mobile.',
            'Coherence des donnees : base relationnelle structuree avec des relations entre utilisateurs, publications, jeux, messages, notifications, badges et taches.',
        ],
    ],
    [
        'heading' => 'Livrables Attendus',
        'bullets' => [
            'Application web fonctionnelle, securisee et testable.',
            'Base de donnees relationnelle structuree.',
            'Interfaces utilisateur dediees aux joueurs.',
            'Interface d administration protegee.',
            'Modules sociaux, gaming, messagerie, notifications et gamification operationnels.',
            'Documentation de conception et de realisation.',
            'Captures d ecran des principales interfaces de l application.',
        ],
    ],
    [
        'heading' => 'Conclusion',
        'paragraphs' => [
            'Nexus est une plateforme sociale gamifiee qui combine les fonctionnalites d un reseau social moderne avec les besoins specifiques du monde gaming. Elle regroupe la gestion de profil, les publications, les interactions, la messagerie, la recherche de joueurs, les notifications, les taches quotidiennes, l XP et les badges dans une seule application coherente.',
            'Grace a son architecture Laravel, Nexus beneficie d une organisation claire, securisee et evolutive. Le projet met l accent sur l engagement des utilisateurs, la creation de liens entre joueurs et la valorisation de l activite communautaire. Il constitue ainsi une base solide pour developper, a l avenir, une plateforme gaming plus complete et plus ambitieuse.',
        ],
    ],
];

function escapeXml(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
}

function paragraph(string $text, string $style = 'Normal'): string
{
    $styleXml = $style !== 'Normal' ? '<w:pPr><w:pStyle w:val="' . escapeXml($style) . '"/></w:pPr>' : '';
    return '<w:p>' . $styleXml . '<w:r><w:t xml:space="preserve">' . escapeXml($text) . '</w:t></w:r></w:p>';
}

function bullet(string $text): string
{
    return '<w:p><w:pPr><w:pStyle w:val="ListParagraph"/><w:numPr><w:ilvl w:val="0"/><w:numId w:val="1"/></w:numPr></w:pPr><w:r><w:t xml:space="preserve">' . escapeXml($text) . '</w:t></w:r></w:p>';
}

$body = paragraph('Cahier des Charges - Projet Nexus', 'Title');
$body .= paragraph('Application web sociale orientee gaming et interaction entre utilisateurs.', 'Subtitle');

foreach ($sections as $section) {
    $body .= paragraph($section['heading'], 'Heading1');

    foreach ($section['paragraphs'] ?? [] as $p) {
        $body .= paragraph($p);
    }

    foreach ($section['bullets'] ?? [] as $b) {
        $body .= bullet($b);
    }
}

$documentXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    . '<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">'
    . '<w:body>'
    . $body
    . '<w:sectPr><w:pgSz w:w="11906" w:h="16838"/><w:pgMar w:top="1440" w:right="1440" w:bottom="1440" w:left="1440" w:header="708" w:footer="708" w:gutter="0"/></w:sectPr>'
    . '</w:body></w:document>';

$stylesXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    . '<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">'
    . '<w:style w:type="paragraph" w:default="1" w:styleId="Normal"><w:name w:val="Normal"/><w:rPr><w:rFonts w:ascii="Calibri" w:hAnsi="Calibri"/><w:sz w:val="22"/></w:rPr><w:pPr><w:spacing w:after="160" w:line="276" w:lineRule="auto"/></w:pPr></w:style>'
    . '<w:style w:type="paragraph" w:styleId="Title"><w:name w:val="Title"/><w:rPr><w:b/><w:sz w:val="36"/></w:rPr><w:pPr><w:jc w:val="center"/><w:spacing w:after="240"/></w:pPr></w:style>'
    . '<w:style w:type="paragraph" w:styleId="Subtitle"><w:name w:val="Subtitle"/><w:rPr><w:i/><w:color w:val="666666"/><w:sz w:val="24"/></w:rPr><w:pPr><w:jc w:val="center"/><w:spacing w:after="360"/></w:pPr></w:style>'
    . '<w:style w:type="paragraph" w:styleId="Heading1"><w:name w:val="heading 1"/><w:basedOn w:val="Normal"/><w:next w:val="Normal"/><w:rPr><w:b/><w:color w:val="4F46E5"/><w:sz w:val="28"/></w:rPr><w:pPr><w:spacing w:before="260" w:after="160"/></w:pPr></w:style>'
    . '<w:style w:type="paragraph" w:styleId="ListParagraph"><w:name w:val="List Paragraph"/><w:basedOn w:val="Normal"/><w:pPr><w:ind w:left="720"/></w:pPr></w:style>'
    . '</w:styles>';

$numberingXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    . '<w:numbering xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">'
    . '<w:abstractNum w:abstractNumId="0"><w:lvl w:ilvl="0"><w:start w:val="1"/><w:numFmt w:val="bullet"/><w:lvlText w:val="•"/><w:lvlJc w:val="left"/><w:pPr><w:ind w:left="720" w:hanging="360"/></w:pPr></w:lvl></w:abstractNum>'
    . '<w:num w:numId="1"><w:abstractNumId w:val="0"/></w:num>'
    . '</w:numbering>';

$contentTypesXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
    . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
    . '<Default Extension="xml" ContentType="application/xml"/>'
    . '<Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>'
    . '<Override PartName="/word/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>'
    . '<Override PartName="/word/numbering.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.numbering+xml"/>'
    . '</Types>';

$relsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
    . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>'
    . '</Relationships>';

$documentRelsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
    . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
    . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
    . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/numbering" Target="numbering.xml"/>'
    . '</Relationships>';

if (!is_dir(dirname($output))) {
    mkdir(dirname($output), 0777, true);
}

if (file_exists($output)) {
    unlink($output);
}

$zip = new ZipArchive();
if ($zip->open($output, ZipArchive::CREATE) !== true) {
    fwrite(STDERR, "Unable to create DOCX\n");
    exit(1);
}

$zip->addFromString('[Content_Types].xml', $contentTypesXml);
$zip->addFromString('_rels/.rels', $relsXml);
$zip->addFromString('word/document.xml', $documentXml);
$zip->addFromString('word/_rels/document.xml.rels', $documentRelsXml);
$zip->addFromString('word/styles.xml', $stylesXml);
$zip->addFromString('word/numbering.xml', $numberingXml);
$zip->close();

echo $output . PHP_EOL;
