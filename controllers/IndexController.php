<?php

if (isset($_SESSION['player'])) {
    header('Location: index.php?route=roulette');
} else {
    header('Location: index.php?route=connexion');
}