# Projet Développer dans le cadre du Cours Symfony à l'ENI

[![forthebadge](http://forthebadge.com/images/badges/built-with-love.svg)](http://forthebadge.com)  [![forthebadge](data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMzEuMzMzMzMyMDYxNzY3NTgiIGhlaWdodD0iMzUiIHZpZXdCb3g9IjAgMCAyMzEuMzMzMzMyMDYxNzY3NTggMzUiPjxyZWN0IHdpZHRoPSI1OC42NjY2Njc5MzgyMzI0MiIgaGVpZ2h0PSIzNSIgZmlsbD0iIzMxQzRGMyIvPjxyZWN0IHg9IjU4LjY2NjY2NzkzODIzMjQyIiB3aWR0aD0iMTAwLjY2NjY2NDEyMzUzNTE2IiBoZWlnaHQ9IjM1IiBmaWxsPSIjMzg5QUQ1Ii8+PHRleHQgeD0iMjkuMzMzMzMzOTY5MTE2MjEiIHk9IjIxLjUiIGZvbnQtc2l6ZT0iMTIiIGZvbnQtZmFtaWx5PSInUm9ib3RvJywgc2Fucy1zZXJpZiIgZmlsbD0iI0ZGRkZGRiIgdGV4dC1hbmNob3I9Im1pZGRsZSIgbGV0dGVyLXNwYWNpbmc9IjIiPldJVEg8L3RleHQ+PHRleHQgeD0iMTA5IiB5PSIyMS41IiBmb250LXNpemU9IjEyIiBmb250LWZhbWlseT0iJ01vbnRzZXJyYXQnLCBzYW5zLXNlcmlmIiBmaWxsPSIjRkZGRkZGIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LXdlaWdodD0iOTAwIiBsZXR0ZXItc3BhY2luZz0iMiI+U1lNRk9OWTwvdGV4dD48cmVjdCB4PSIxNTkuMzMzMzMyMDYxNzY3NTgiIHdpZHRoPSI3MiIgaGVpZ2h0PSIzNSIgZmlsbD0iIzI2NzRBNCIvPjx0ZXh0IHg9IjE5NS4zMzMzMzIwNjE3Njc1OCIgeT0iMTcuNSIgZm9udC1zaXplPSIxMiIgZm9udC1mYW1pbHk9IidSb2JvdG8nLCBzYW5zLXNlcmlmIiBmaWxsPSIjRkZGRkZGIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LXdlaWdodD0iNTAwIiBhbGlnbm1lbnQtYmFzZWxpbmU9Im1pZGRsZSIgbGV0dGVyLXNwYWNpbmc9IjIiPklOU0lERTwvdGV4dD48L3N2Zz4=)](https://forthebadge.com)

La société ENI souhaite développer pour ses stagiaires actifs ainsi que ses anciens stagiaires une plateforme web leur permettant d’organiser des sorties.

La plateforme est une plateforme privée dont l’inscription sera gérée par le ou les administrateurs.

Les sorties ainsi que les participants sont rattachés à un campus pour permettre une organisation géographique des sorties.

## Pour commencer

Le projet nécessite à minima un serveur Web, du PHP et une base de donnée.

### Pré-requis

- Symfony 6.4
- PHP 8.1 ou supérieur
- BdD (MySQL 10, MariaDB 11, PostgreSQL 17, ... )

### Installation

Les étapes pour installer le programme :

1 : Dézipper dans le répertoire de votre choix

2 : Ouvrir une ligne de commande dans ce répertoire

3 : Exécuter la commande ``symfony composer update`` afin de récupérer tous les vendors nécessaires

4 : Créer un fichier .env.local afin d'y mettre les paramètres suivants que vous pouvez personnaliser suivant vos besoins
````YAML
DATABASE_URL="mysql://user:password@127.0.0.1:3306/nom_database?serverVersion=9.1.0&charset=utf8mb4"

MAILER_DSN=smtp://localhost

JWT_PASSPHRASE=ma_pass_phrase
````
Si vous configurez le mailer en localhost vous pourrez utiliser un logiciel comme Papercut afin de recevoir les mails envoyés.

5 : Créer l'utilisateur sur votre SGBD avec les droits suffisant afin de créer une nouvelle database et toute la structure.

6 : Exécuter la commande ``symfony console doctrine:database:create`` pour créer la base

7 : Exécuter la commande ``symfony console doctrine:migrations:migrate`` pour créer toute la structure

8 : Exécuter la commande ``symfony console doctrine:fixtures:load`` pour hydrater la base avec des données de test

## Démarrage

1 : Exécuter la commande ``symfony serve -d`` afin de lancer le serveur

2 : Puis vous pouvez vous connecter sur votre application avec la commande ``symfony open:local`` qui ouvrira sur votre navigateur par défaut une page à l'adresse de votre serveur http://127.0.0.1:80000 par défaut

_(Dans un premier temps vous pouvez vous connecter avec l'utilisateur "admin@eni.com" mot de passe "admin" puis créer votre utilisateur ou en utiliser 1 déjà créer
le mot de passe des utilisateurs est par défaut leur prénom)_ 
![Exemple-connexion.png](Exemple-connexion.png)

## Fabriqué avec


* [Bootstrap.css](https://getbootstrap.com/docs/5.3/getting-started/introduction/) - Framework CSS (front-end)
* [JWT-authentication-bundle](lexik/jwt-authentication-bundle) - Sécurité des API
* [PHPStorm](https://www.jetbrains.com/fr-fr/phpstorm/) - IDE pour PHP - Symfony

## Versions

**Dernière version :** 0.4

## Auteurs
* **Chedi** _alias_ [@chedighribi](https://github.com/chedighribi)
* **Pierrick** _alias_ [@Pierrick-k](https://github.com/Pierrick-k)
* **Valentin** _alias_ [@ValentinJG](https://github.com/ValentinJG)

Lisez la liste des [contributeurs](https://github.com/Pierrick-k/sortie-eni/graphs/contributors) pour voir qui à aidé au projet !

_(pour le lien mettez simplement l'URL de votre projet suivi de ``/contirubors``)_

## License

Ce projet est sous licence ``CC BY NC ND`` - voir le fichier [LICENSE.md](LICENSE.md) pour plus d'informations


