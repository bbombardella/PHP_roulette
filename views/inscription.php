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
                    <form method="POST" action="./index.php?route=inscription">
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