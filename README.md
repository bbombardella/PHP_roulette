# Roulette

Rendu du TP PHP - Roulette par ***Bastien BOMBARDELLA*** en classe de G6S3.

## Installation

### Création de la base de données
Pour importer la structure de la base de données, il faut utiliser le fichier *« roulette.sql »*.

### Connexion à la base de données
Pour permettre au code PHP de se connecter à la base de données, il suffira de modifier les informations contenues dans la classe **DB.php** qui se trouve dans le dossier 
*/models*.

Voici la ligne *(n°12)* contenant les informations :
```php
public function __construct($h = 'localhost:3308', $d = 'roulette', $u = 'root', $p = 'root')
```

*$h*, *$d*, *$u* et *$p* sont les variables à changer dans le constructeur.