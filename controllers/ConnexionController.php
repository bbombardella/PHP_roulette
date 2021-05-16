<?php

//Gestion des différentes erreurs
//Cela permet de simplifier la gestion de l'affichage dans le code HTML/PHP
$errors = array(
    'sections' => false,
    'username' => false,
    'password' => false,
);

//Je vérifie s'il y a eu une rêquete POST, sinon j'affiche ma page "normalement"
if (!isset($_SESSION['player'])) {
    if (count($_POST) > 0) {
        //Je vérifie que tous les champs aient été remplis
        if (isset($_POST['connexionCompleted']) && isset($_POST['username']) && isset($_POST['password'])) {
            $player_dao = new DAO_Player();
            //Je récupére les données de connexion de l'utilisateur
            $reponse = $player_dao->connection($_POST['username'], $_POST['password']);
            //Si l'utilisateur a été trouvé dans la base de données
            if ($reponse['success']) {
                //Je redirige vers la page du jeu roulette pour commencer le jeu
                header('Location: index.php?route=roulette');
            } else {
                $errors['username'] = $reponse['errors']['username'];
                $errors['password'] = $reponse['errors']['password'];
            }
        } else {
            $errors['sections'] = true;
        }
    }
} else {
    header('Location: index.php?route=roulette');
}
require_once 'views/connexion.php';
