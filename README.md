<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Design

Pour coder les pages, il faut suivre la maquette Figma :

> **[Lien de la maquette Figma](https://www.figma.com/design/0pmIhHAnn79f8gmy7klqZI/Laurelin?node-id=1669-162202&t=ZaVNb88bFe8RkRFk-1)**

## Lancement du serveur de développement

+ Pour lancer le serveur de développement : `composer run dev`
+ Le serveur se lance à l'adresse : [http://127.0.0.1:8000/](http://127.0.0.1:8000/)

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

## Indications Laravel

Pour recréer la base de données, il faut utiliser :  
```shell
php artisan migrate
```
