<?php

//Gestion des différentes erreurs
//Cela permet de simplifier la gestion de l'affichage dans le code HTML/PHP
$errors = array(
    'username' => false,
    'password' => false,
    'completed' => false,
    'alreadyExists' => false,
);

//Je vérifie s'il y a eu une rêquete POST, sinon j'affiche ma page "normalement"
if (count($_POST) > 0) {
    //Je vérifie que tous les champs aient été remplis
    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['inscriptionCompleted'])) {
        $db = new DB();
        //J'ajoute le nouvel utilisateur dans la base de données
        $response = $db->addPlayer($_POST['username'], $_POST['password']);

        //Si l'insertion est un succès
        if ($response['success']) {
            //Je redirige vers la page du jeu roulette pour commencer le jeu
            header('Location: index.php?route=roulette.php');
        } else {
            //Sinon ce que l'utilisateur exite déjà dans la base de données
            $errors['alreadyExists'] = $response['errors']['alreadyExists'];
        }
    } else {
        if (!isset($errors['username'])) {
            $errors['username'] = true;
        }
        if (!isset($errors['password'])) {
            $errors['password'] = true;
        }
        if (!isset($_POST['inscriptionCompleted'])) {
            $errors['completed'] = true;
        }
    }
}

require_once 'views/inscription.php';