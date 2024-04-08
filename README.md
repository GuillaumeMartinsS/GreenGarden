# GreenGarden

Projet site de potager virtuel.
Mini jeu permettant de gagner des points en s'occupant de plantes jusqu'à leur floraison ou fructification. 
L'application est reliée à une l'API météo OpenWeather et reçoit ainsi les données météo du jour de Paris pour mettre à jour le potager.
Ici la partie back-end développée à l'aide de PHP et du framework Symfony. Il s'agit d'une API Rest.

Fonctionnalités de l'application : 
• Création d'un compte utilisateur 
• Connexion au compte 
• Modification et personnalisation du compte 
• Avoir accès à son jardin
• Planter une plante
• Mettre à jour le jardin viens un bouton (modification de l'âge des plantes et de leur niveau d'hydratation en fonction de la météo)
• Mettre à jour le jardin automatiquement via un événement (modification de l'âge des plantes et de leur niveau d'hydratation en fonction de la météo)
• Envoie d'emails de confirmation de création de compte et d'alerte lorsqu'une plante atteint un niveau d'hydratation fragile.
• Réalisation de test avec PHPUnit

Technologies utilisées : 
• PHP 
• Symfony - framework PHP
• Doctrine – ORM PHP
• MariaDB - Base De Données (BDD) utilisée 
• SQL - langage pour l’exploitation de la BDD 
• JWT Lexik - authentification des utilisateurs 
• PHPUnit - réalisation de tests
• Faker - génération de données factices 
• Insomnia - outil de test pour API
