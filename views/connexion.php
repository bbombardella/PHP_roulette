<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion -- Roulette</title>
    <?php include 'views/style.php'; ?>
</head>

<body style="min-height:100vh;min-width:100vw;">
    <main class="d-flex align-items-center flex-column justify-content-center min-vh-100">
        <div class="container align-self-start">
            <div class="row py-3">
                <div class="col-12">
                    <a href="./index.php?route=inscription" class="d-block text-end streched-link">S'inscrire</a>
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
                    <form method="POST" action="./index.php?route=connexion">
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