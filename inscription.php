<?php

//Démarre la session pour suivre les données du joueur
session_start();

//J'inclus ma classe DB
require_once('./classes/DB.php');

//Gestion des différentes erreurs
//Cela permet de simplifier la gestion de l'affichage dans le code HTML/PHP
$errors = array(
    'username' => false,
    'password' => false,
    'completed' => false,
    'alreadyExists' => false
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
            header('Location: roulette.php');
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

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription -- Roulette</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

</head>

<body style="min-height:100vh;min-width:100vw;">
    <main class="d-flex align-items-center flex-column justify-content-center min-vh-100">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h1><strong>Inscription</strong></h1>
                </div>
            </div>
            <?php
            if ($errors['completed']) {
                echo ('
                        <div class="row text-center mb-5">
                            <div class="col-12">
                                <div class="alert alert-danger" role="alert">
                                    Merci de compléter tous les champs de saisie avec le formulaire!
                                </div>
                            </div>
                        </div>
                    ');
            }
            ?>
            <div class="row text-center mb-5">
                <div class="col-12">
                    <img src="./assets/img/roulette.svg" alt="Roulette" style="height:20vh;object-fit:contain;">
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-6 col-10">
                    <form method="POST" action="./inscription.php">
                        <div class="form-floating mb-3">
                            <?php
                            if ($errors['username']) {
                                echo ('
                                        <input type="text" name="username" id="username" class="form-control is-invalid" placeholder="name@example.com" required>
                                        <label for="username" class="form-label">Identifiant</label>
                                        <div class="invalid-feedback">
                                            Merci de saisir un identifiant !
                                        </div>
                                ');
                            } else if ($errors['alreadyExists']) {
                                echo ('
                                        <input type="text" name="username" id="username" class="form-control is-invalid" placeholder="name@example.com" required>
                                        <label for="username" class="form-label">Identifiant</label>
                                        <div class="invalid-feedback">
                                            Le nom d\'utilisateur existe déjà !
                                        </div>
                                ');
                            } else {
                                echo ('
                                        <input type="text" name="username" id="username" class="form-control" placeholder="name@example.com" required>
                                        <label for="username" class="form-label">Identifiant</label>
                                ');
                            }
                            ?>
                        </div>
                        <div class="form-floating mb-3">
                            <?php
                            if ($errors['password']) {
                                echo ('
                                    <input type="password" name="password" id="password" class="form-control is-invalid" placeholder="name@example.com" required>
                                    <label for="username" class="form-label">Mot de passe</label>
                                    <div class="invalid-feedback">
                                            Merci de saisir un mot de passe !
                                    </div>
                                ');
                            } else {
                                echo ('
                                    <input type="password" name="password" id="password" class="form-control" placeholder="name@example.com" required>
                                    <label for="username" class="form-label">Mot de passe</label>
                                ');
                            }
                            ?>
                        </div>
                        <div class="mt-4">
                            <input type="hidden" name="inscriptionCompleted">
                            <button type="submit" class="btn btn-primary mx-1">S'inscrire</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>


</body>

</html>