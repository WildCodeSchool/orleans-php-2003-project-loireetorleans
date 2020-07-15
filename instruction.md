# Projet 3 - Loire&Orléans

Loire et Orléans est un site qui s'adresse à des ambassadeurs qui sont issus des différents bassins d'emploi du département. 
Le site a pour but de mettre en contact les ambassadeurs avec Loire et Orléans via des partages de documents que l'ambassadeur
peut télécharger et un espace de commentaires ou l'on peut poser des questions au sujet d'un document.

## Version
php : 7.2
Symfony : 4.4.10

## Démarrer un projet

### Pré-requis :

Vérifiez que composer est installé
Vérifiez que yarn et node sont installés

### Installation :

1. Clonez ce projet avec la commande `git clone <urlDuProjet>`
2. Dans le terminal, lancez la commande `composer install`
3. Dans le terminal, lancez la commande `yarn install`
4. Créez le fichier .env.local dans votre dossier en effectuant une copie du fichier .env déjà présent.
	- paramétrez votre base de données en entrant votre nom d'utilisateur, mot de passe et nom de la base de données
	- paramétrez le mailer
5. Dans le terminal, initialisez votre base de données avec la commande `php bin/console doctrine:database:create`
6. Dans le terminal, chargez la base de données avec  la commande `php bin/console doctrine:schema:update --force`
7. Dans le terminal, chargez les fixtures avec la commande `php bin/console doctrine:fixtures:load`

### Travailler sur le projet :

1. Lancez la commande `symfony server:start` pour lancer votre serveur local
2. Lancez la commande `yarn encore dev --watch` afin de charger les assets
Avant de vous connecter côté ambassadeur, il vous faut valider au moins un profil côté administrateur (page gestion de profils). Vous pourrez ensuite vous connecteur avec le login correspondant en base de données.

### Auteurs :

Élèves de la Wild Code School
