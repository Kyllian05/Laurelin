<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Lancement du serveur de développement

+ Pour lancer le serveur de développement : `composer run dev`
+ Le serveur se lance à l'adresse : [http://127.0.0.1:8000/](http://127.0.0.1:8000/)

## Prérequis

Avant de commencer, assurez-vous d'avoir les éléments suivants installés sur votre machine :

- **PHP** (>= 8.1)
- **Composer** (gestionnaire de dépendances PHP)
- **Node.js** (>= 16.x) et **npm** (pour les assets frontend)
- **Base de données** (SQLite et MariaDB)

## Installation

1. Clonez ce dépôt
2. `composer install` (des dépendances peuvent être nécessaires)
3. `cp .env.example .env`
4. `php artisan key:generate`
5. `php artisan migrate`
6. `npm install && npm run build`

## Technologies choisies

+ Framework PHP : **[Laravel](https://laravel.com/)**
+ Framework JS : **[Vue.js](https://vuejs.org/)**
+ Intégration Laravel et Vue : **[Inertia](https://inertiajs.com/)**