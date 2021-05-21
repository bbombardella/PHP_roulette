<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jouer -- Roulette</title>
    <?php include 'views/style.php'; ?>
</head>

<body style="min-height:100vh;min-width:100vw;max-width:100vw">

    <main class="bg-light">
        <div class="d-flex align-items-center flex-column justify-content-center min-vh-100 position-relative">
            <div class="container align-self-start">
                <div class="row py-3">
                    <div class="col-6">
                        <p><ins>Votre balance :</ins> <mark><?= $_SESSION['player']->money ?>€</mark></p>
                    </div>
                    <div class="col-5">
                        <a href="./index.php?route=deconnexion" class="d-block text-end streched-link">Se déconnecter</a>
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
                        <h2><small class="text-muted"><?= $_SESSION['player']->name ?></small></h2>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-9">
                        <form action="./index.php?route=roulette" method="post">
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