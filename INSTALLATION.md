<p align="center"><img src="./public/images/logo.png" width="300" alt="Laravel Logo"></a></p>

Ce fichier regroupe les instructions pour mettre en place le projet pour développer.

# Sommaire

+ [Indication pour dévelloper](#indication-pour-dévelloper)
+ [Procédure d'installation du projet](#procédure-dinstallation-du-projet)

# Indication pour dévelloper

### Lancement du serveur de développement

+ Pour lancer le serveur de développement : `composer run dev`
+ Le serveur se lance à l'adresse : [http://127.0.0.1:8000/](http://127.0.0.1:8000/)
+ (Sur la VM c'est différent)
+ S'il manque des dépendances lancer `composer install` et `npm install`

### Les pages

+ Les fichiers des pages sont dans `ressources/js/Pages`
+ Il faut utliser les composants Vue créés qui sont dans `ressources/js/Pages/Components`

### Indications Laravel

Pour recréer la base de données et la remplir, il faut utiliser :
```shell
php artisan migrate:fresh --seed
```

Pour créer les liens symboliques avec les fichiers du storage :
```shell
php artisan storage:link
```

Pour créer un model, une migration, etc.
```shell
php aritsan make:[model, migration, ...] [nom fichier]
```

### Design

+ Pour coder les pages, il faut suivre la maquette Figma.
+ Il y a des classes CSS globales qui sont définies dans `ressources/css/app.css`
+ Pour les icônes, on utilise [Google icons](https://fonts.google.com/icons?icon.set=Material+Symbols&icon.style=Rounded) (déjà importé), vous avez juste à ajouter l'élément `<span>`
+ Les polices aussi sont déjà ajoutées : `Tenor Sans`, `Parisienne`, `Poppins`

### Sites de bijoux pour inspiration

+ https://www.cartier.com/fr-fr/
+ https://www.tiffany.fr/
+ https://fr.pandora.net/fr/
+ https://www.prada.com/fr/fr/fine-jewelry/fine-jewelry-collection/c/10628EU
+ https://www.dior.com/fr_fr/fashion/joaillerie-horlogerie/joaillerie-par-categorie/toute-la-joaillerie
+ https://www.chanel.com/fr/joaillerie/
+ https://fr.arthusbertrand.com/
+ https://www.hermes.com/fr/fr/category/bijouterie/bijoux-en-or/#|

### Tutoriels

+ [Tutoriel Vue.js](https://grafikart.fr/formations/vuejs)
+ [Tutoriel Laravel](https://grafikart.fr/formations/laravel)

# Procédure d'installation du projet

## Prérequis

Avant de commencer, assurez-vous d'avoir les éléments suivants installés sur votre machine :

- **PHP** (>= 8.1)
- **Composer** (gestionnaire de dépendances PHP)
- **Node.js** (>= 16.x) et **npm** (pour les assets frontend)
- **Base de données :** MariaDB ([voir installation MariaDB](#installation-mariadb))

## Installation

1. Clonez ce dépôt

2. Lancer Composer (des dépendances peuvent être nécessaires)
```shell
composer install
```
3. Copier le `.env`
```shell
cp .env.example .env
```
4. Générer la key
```shell
php artisan key:generate
```
5. Installer les lib JS
```shell
npm install && npm run build
```

### Installation MariaDB

Installer MariaDB :
```shell
sudo apt install mariadb-server
```

Installer le driver :
```shell
sudo apt install php-mysql
```

Lancer le service :
```shell
sudo systemctl start mariadb
sudo systemctl enable mariadb
```

Vérifier que la DB est lancée :
```shell
sudo systemctl status mariadb
```

Se connecter à la DB :
```shell
sudo mariadb
```

Dans la DB exécuter ces commandes :
```SQL
GRANT ALL ON *.* TO 'testuser'@'localhost' IDENTIFIED BY 'pswd1234' WITH GRANT OPTION;
FLUSH PRIVILEGES;
CREATE DATABASE laravel;
```

Dans le répertoire du projet recopier le `.env` :
```shell
cp .env.example .env
```

Pour vérifier que ça fonctionne :
```shell
php artisan migrate
```

## Technologies choisies

+ Framework PHP : **[Laravel](https://laravel.com/)**
+ Framework JS : **[Vue.js](https://vuejs.org/)**
+ Intégration Laravel et Vue : **[Inertia](https://inertiajs.com/)**
