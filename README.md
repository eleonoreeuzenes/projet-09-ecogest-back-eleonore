# Ecogest backend API : 

## Prérequis : 

* avoir PHP version 8.1 ou 8.2
* avoir composer d'installer

## Créer le fichier .env

`cp .env.example .env`

## Installer les dépendances

`composer install`

## Lancer docker

`docker-compose up -d`
ou
`docker compose up -d`

## Générer la clé

`vendor/bin/sail artisan key:generate`


https://kourou.oclock.io/ressources/recap-quotidien/meduse-e13-sail-orm-eloquent-migrations-seeders/


https://laravel.com/docs/10.x/sail