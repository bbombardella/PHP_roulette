<?php

//Je dois déclarer mes classes avant le session_start() car sinon j'obtiens
//__PHP_Incomplete_Class Object quand je stocke un objet dans la classe session
require 'models/DB.php';
require 'models/Game/DAO_Game.php';
require 'models/Player/DAO_Player.php';

//Démarre la session pour suivre les données du joueur
session_start();

$route = $_GET['route'];

switch ($route) {
    case '':
        require_once 'controllers/IndexController.php';
        break;
    case 'connexion':
        require_once 'controllers/ConnexionController.php';
        break;
    case 'inscription':
        require_once 'controllers/InscriptionController.php';
        break;
    case 'deconnexion':
        //Je détruis la session à propos du joueur
        session_destroy();
        require_once 'views/deconnexion.php';
        break;
    case 'roulette':
        require_once 'controllers/RouletteController.php';
        break;
    case 'session':
        require_once 'views/session.php';
        break;
    default:
        //erreur 404
        echo ('404');
        break;
}
