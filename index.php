<?php

include('models/Player/DTO_Player.php');

//Démarre la session pour suivre les données du joueur
session_start();

$route=$_GET['route'];

switch ($route) {
    case '':
        require_once('controllers/IndexController.php');
        break;
    case 'connexion':
        require_once('controllers/ConnexionController.php');
        break;
    case 'inscription':
        require_once('controllers/InscriptionController.php');
        break;
    case 'deconnexion':
        //Je détruis la session à propos du joueur
        session_destroy();
        include_once('views/deconnexion.php');
        break;
    case 'roulette':
        require_once('controllers/RouletteController.php');
        break;
    case 'session':
        include_once('views/session.php');
        break;
    default:
        //erreur 404
        echo ('404');
        break;
}
