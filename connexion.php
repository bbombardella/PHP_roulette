<?php

//Démarre la session pour suivre les données du joueur
session_start();

//Gestion des différentes erreurs
//Cela permet de simplifier la gestion de l'affichage dans le code HTML/PHP
$errors = array(
    'sections' => false,
    'username' => false,
    'password' => false
);

//Permet de déterminer si je dois afficher une fenêtre
//m'indiquant que je sois bien déconnecté.e
$deco = false;

//Le cas où une déconnexion est demandé
if (isset($_GET['deco'])) {
    //Je détruis la session à propos du joueur
    session_destroy();
    //J'indique que c'est bien une déconnexion que je traite
    $deco = true;

    //Le cas où je suis déjà connecté, je redirige sur la page du jeu roulette
} else if (isset($_SESSION['username'])) {
    header('Location: roulette.php');
} else {

    //Je vérifie s'il y a eu une rêquete POST, sinon j'affiche ma page "normalement"
    if (count($_POST) > 0) {
        //Je vérifie que tous les champs aient été remplis
        if (isset($_POST['connexionCompleted']) && isset($_POST['username']) && isset($_POST['password'])) {
            //Je récupére les données de connexion de l'utilisateur
            try {
                $db = new PDO('mysql:host=localhost:3308;dbname=roulette;charset=utf8;', 'root', 'root');
                $query = 'SELECT * FROM player WHERE name=?';
                $prepared = $db->prepare($query);
                $prepared->execute(array($_POST['username']));
                $data = $prepared->fetch();
            } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }
            //Si l'utilisateur a été trouvé dans la base de données
            if ($data != null) {
                //Je compare les hashages BCRYPT des mots de passes
                if (password_verify($_POST['password'], $data['password'])) {
                    //J'enregistre les données de l'utilisateur dans le tableau SESSION
                    $_SESSION['iduser'] = $data['id'];
                    $_SESSION['username'] = $data['name'];
                    $_SESSION['money'] = $data['money'];
                    //Je redirige vers la page du jeu roulette pour commencer le jeu
                    header('Location: roulette.php');
                } else {
                    $errors['password'] = true;
                }
            } else {
                $errors['username'] = true;
            }
        } else {
            $errors['sections'] = true;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion -- Roulette</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

</head>

<body style="min-height:100vh;min-width:100vw;">
    <main class="d-flex align-items-center flex-column justify-content-center min-vh-100">
        <?php
        if ($deco) {
            echo ('
                <div class="alert alert-success" role="alert">
                    Vous avez été déconnecté avec succès !
                </div>
            ');
        }
        ?>
        <div class="container align-self-start">
                <div class="row py-3">
                    <div class="col-12">
                        <a href="./inscription.php" class="d-block text-end streched-link">S'inscrire</a>
                    </div>
                </div>
            </div>
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h1><strong>Connectez-vous pour jouer à la roulette</strong></h1>
                </div>
            </div>
            <div class="row text-center mb-5">
                <div class="col-12">
                    <img src="./assets/img/roulette.svg" alt="Roulette" style="height:20vh;object-fit:contain;">
                </div>
            </div>
            <div class="row justify-content-center mb-5">
                <div class="col-lg-6 col-10">
                    <form method="POST" action="./connexion.php">
                        <div class="form-floating mb-3">
                            <?php
                            if ($errors['sections']) {
                                echo ('
                                        <input type="text" name="username" id="username" class="form-control is-invalid" placeholder="name@example.com" required>
                                        <label for="username" class="form-label">Identifiant</label>
                                        <div class="invalid-feedback">
                                            Merci de compléter tous les champs
                                        </div>
                                    ');
                            } else if ($errors['username']) {
                                echo ('
                                        <input type="text" name="username" id="username" class="form-control is-invalid" placeholder="name@example.com" required>
                                        <label for="username" class="form-label">Identifiant</label>
                                        <div class="invalid-feedback">
                                            Identifiant incorrect !
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
                            if ($errors['sections']) {
                                echo ('
                                        <input type="password" name="password" id="password" class="form-control is-invalid" placeholder="name@example.com" required>
                                        <label for="username" class="form-label">Mot de passe</label>
                                        <div class="invalid-feedback">
                                            Merci de compléter tous les champs
                                        </div>
                                    ');
                            } else if ($errors['password']) {
                                echo ('
                                        <input type="password" name="password" id="password" class="form-control is-invalid" placeholder="name@example.com" required>
                                        <label for="username" class="form-label">Mot de passe</label>
                                        <div class="invalid-feedback">
                                            Mot de passe invalide !
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
                            <input type="hidden" name="connexionCompleted">
                            <button type="reset" class="btn btn-outline-warning">Effacer</button>
                            <button type="submit" class="btn btn-primary mx-1">Se connecter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>


</body>

</html>