<?php

//Démarre la session pour accéder aux infos du joueur
session_start();

//Je redirige sur la page de connexion s'il n'existe pas de session sur le joueur
if (!isset($_SESSION['username'])) {
    header('Location: connexion.php');
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

            //Dans tous les cas, j'ajoute cette ligne de jeu dans la base de données
            //et je mets à jouer l'argent disponible du joueur
            try {
                //Connexion à la base de données
                $db = new PDO('mysql:host=localhost:3308;dbname=roulette;charset=utf8;', 'root', 'root');
                //Insertion de informations sur le jeu
                //Création requête + préparation + éxécution
                $query = 'INSERT INTO game(id,player,date,bet,profit) VALUES (DEFAULT,:v_player,DEFAULT,:v_bet,:v_profit)';
                $prepared = $db->prepare($query);
                $prepared->execute(array(
                    'v_player' => $_SESSION['iduser'],
                    'v_bet' => $_POST['mise'],
                    'v_profit' => $gain
                ));

                //Mise à jour de l'argent du joueur
                //Création requête + préparation + éxécution
                $query = 'UPDATE player SET money=v_money WHERE id=v_player_id';
                $prepared = $db->prepare($query);
                $prepared->execute(array(
                    'v_money' => $_SESSION['money'],
                    'v_player_id' => $_SESSION['iduser']
                ));
            } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }
        } else {
            $tirage_result['success'] = false;
            $tirage_result['message'] = 'Votre mise est supérieure à votre balance';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jouer -- Roulette</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
</head>

<body style="min-height:100vh;min-width:100vw;max-width:100vw">

    <main class="bg-light">
        <div class="d-flex align-items-center flex-column justify-content-center min-vh-100 position-relative">
            <div class="container align-self-start">
                <div class="row py-3">
                    <div class="col-6">
                        <p><ins>Votre balance :</ins> <mark><?= $_SESSION['money'] ?>€</mark></p>
                    </div>
                    <div class="col-5">
                        <a href="./connexion.php?deco" class="d-block text-end streched-link">Se déconnecter</a>
                    </div>
                </div>
            </div>
            <?php
            if (isset($tirage_result['success'])) {
                echo ('
                        <div class="container">
                            <div class="row align-items-center justify-content-center">
                                <div class="col-10">
                    ');
                if ($tirage_result['success']) {
                    echo ('
                            <div class="alert alert-success" role="alert">
                                ' . $tirage_result['message'] . '
                            </div>
                        ');
                } else {
                    echo ('
                            <div class="alert alert-danger" role="alert">
                                ' . $tirage_result['message'] . '
                            </div>
                        ');
                }
                echo ('
                                </div>
                            </div>
                        </div>
                    ');
            }
            ?>
            <div class="container">
                <div class="row text-center mb-5">
                    <div class="col-12">
                        <h1>Le jeu de la roulette</h1>
                    </div>
                </div>
                <div class="row text-center mb-5">
                    <div class="col-12">
                        <h2><small class="text-muted"><?= $_SESSION['username'] ?></small></h2>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-9">
                        <form action="./roulette.php" method="post">
                            <div class="form-floating mb-5">
                                <input class="form-control form-control-sm" type="text" name="mise" placeholder="Votre mise" required>
                                <label class="form-label" for="mise">Votre mise</label>
                            </div>
                            <div class="row">
                                <div class="col-5">
                                    <div class="form-group">
                                        <label class="form-label" for="nombre"><strong>Miser sur un nombre</strong></label>
                                        <input class="form-control" type="number" name="nombre" id="nombre" min="1" max="36">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="d-flex justify-content-center align-items-center h-100">
                                        <p class="fs-4">OU</p>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <p class="text-center"><strong>Miser sur la parité</strong></p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="parite" id="pair" value="pair" checked>
                                            <label class="form-check-label" for="pair">Pair</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="parite" id="impair" value="impair">
                                            <label class="form-check-label" for="impair">Impair</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit" name="btnJouer">Jouer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>