<?php

//Démarre la session pour suivre les données du joueur
session_start();

require_once('classes/DB.php');

$route=$_GET['route'];

var_dump($_GET['route']);
var_dump($route);

switch ($route) {
    case '':
        if (isset($_SESSION['username'])) {
            header('Location: index.php?route=roulette');
        } else {
            header('Location: index.php?route=connexion');
        }
        break;
    case 'connexion':
        //Gestion des différentes erreurs
        //Cela permet de simplifier la gestion de l'affichage dans le code HTML/PHP
        $errors = array(
            'sections' => false,
            'username' => false,
            'password' => false
        );

        //Je vérifie s'il y a eu une rêquete POST, sinon j'affiche ma page "normalement"
        if (count($_POST) > 0) {
            //Je vérifie que tous les champs aient été remplis
            if (isset($_POST['connexionCompleted']) && isset($_POST['username']) && isset($_POST['password'])) {
                $db = new DB();
                //Je récupére les données de connexion de l'utilisateur
                $reponse = $db->playerConnection($_POST['username'], $_POST['password']);
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
        require_once('views/connexion.php');
        break;
    case 'inscription':
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

        require_once('views/inscription.php');
        break;
    case 'deconnexion':
        //Je détruis la session à propos du joueur
        session_destroy();
        break;
    case 'roulette':
        //Je redirige sur la page de connexion s'il n'existe pas de session sur le joueur
        if (!isset($_SESSION['username'])) {
            header('Location: index.php?route=connexion');
        }

        //Gestion des états du jeu (si le joueur à gagné ou non, et le message en explication)
        $tirage_result = array(
            'success' => null,
            'message' => null
        );

        //Je vérifie s'il y a eu une rêquete POST, sinon j'affiche ma page "normalement"
        if (count($_POST) > 0) {
            //Je vérifie que tous les champs aient été remplis
            if (isset($_POST['mise']) && isset($_POST['nombre']) && isset($_POST['parite']) && isset($_POST['btnJouer'])) {
                //Je vérifie que le jouer est assez d'argent pour jouer
                if ($_SESSION['money'] > $_POST['mise']) {
                    $_SESSION['money'] -= $_POST['mise'];
                    $numero = rand(1, 36);

                    //Si on n'a pas préciser de nombre, ce que l'on souhaite jouer sur la parité
                    if ($_POST['nombre'] == '') {
                        $pair = $numero % 2 == 0;
                        $gain = ($_POST['mise'] * 2);

                        //Si le chiffre est pair
                        if ($pair) {

                            //Si on a miser sur le fait que le chiffre soit pair et qu'il est pair
                            if ($_POST['parite'] == 'pair') {
                                //Définition du gain
                                $gain = $_POST['mise'] * 2;
                                //Mise à jour de l'argent disponible
                                $_SESSION['money'] += $gain;
                                //Gestion des états
                                $tirage_result['success'] = true;
                                //Message associé
                                $tirage_result['message'] = "Vous pensiez que le résultat serait pair et le $numero a été tiré. Bravo, vous gagnez {$gain}€";
                            } else {
                                $gain = 0;
                                $tirage_result['success'] = false;
                                $tirage_result['message'] = "Vous avez misé sur nombre pair, hélas c'est le $numero qui est tombé. Vous avez perdu(e).";
                            }
                        } else {

                            //Si on a miser sur le fait que le chiffre soit impair et qu'il est impair
                            if ($_POST['parite'] == 'impair') {
                                $gain = $_POST['mise'] * 2;
                                $_SESSION['money'] += $gain;
                                $tirage_result['success'] = true;
                                $tirage_result['message'] = "Vous pensiez que le résultat serait impair et le $numero a été tiré. Bravo, vous gagnez {$gain}€";
                            } else {
                                $gain = 0;
                                $tirage_result['success'] = false;
                                $tirage_result['message'] = "Vous avez misé sur nombre impair, hélas c'est le $numero qui est tombé. Vous avez perdu(e).";
                            }
                        }
                    } else {
                        //Si on tombe parfaitement sur le numéro parié
                        if ($_POST['nombre'] == $numero) {
                            $gain = $_POST['mise'] * 35;
                            $_SESSION['money'] += $gain;
                            $tirage_result['success'] = true;
                            $tirage_result['message'] = "Vous avez misé sur le {$_POST['mise']}, et vous avez gagné !";
                        } else {
                            $gain = 0;
                            $tirage_result['success'] = false;
                            $tirage_result['message'] = "Vous avez misé sur le {$_POST['mise']}, hélas c'est le $numero qui est tombé. Vous avez perdu(e).";
                        }
                    }

                    var_dump($_SESSION);

                    //Dans tous les cas, j'ajoute cette ligne de jeu dans la base de données
                    //et je mets à jouer l'argent disponible du joueur
                    $db = new DB();

                    $db->addGamePlayer($_SESSION['iduser'], $_POST['mise'], $gain, $_SESSION['money']);
                } else {
                    $tirage_result['success'] = false;
                    $tirage_result['message'] = 'Votre mise est supérieure à votre balance';
                }
            }
        };
        require_once('views/roulette.php');
        break;
    default:
        //erreur 404
        echo ('404');
        break;
}
