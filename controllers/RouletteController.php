<?php

//Je redirige sur la page de connexion s'il n'existe pas de session sur le joueur
if (!isset($_SESSION['player'])) {
    header('Location: index.php?route=connexion');
}

//Gestion des états du jeu (si le joueur à gagné ou non, et le message en explication)
$tirage_result = array(
    'success' => null,
    'message' => null,
);

//Je vérifie s'il y a eu une rêquete POST, sinon j'affiche ma page "normalement"
if (count($_POST) > 0) {
    //Je vérifie que tous les champs aient été remplis
    if (isset($_POST['mise']) && isset($_POST['nombre']) && isset($_POST['parite']) && isset($_POST['btnJouer'])) {
        //Je vérifie que le jouer est assez d'argent pour jouer
        if ($_SESSION['player']->balance > $_POST['mise']) {
            $_SESSION['player']->balance -= $_POST['mise'];
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
                        $_SESSION['player']->balance += $gain;
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
                        $_SESSION['player']->balance += $gain;
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
                    $_SESSION['player']->balance += $gain;
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

            $db->addGamePlayer($_SESSION['iduser'], $_POST['mise'], $gain, $_SESSION['player']->balance);
        } else {
            $tirage_result['success'] = false;
            $tirage_result['message'] = 'Votre mise est supérieure à votre balance';
        }
    }
}
;
require_once 'views/roulette.php';
