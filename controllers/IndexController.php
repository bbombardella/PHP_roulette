<?php

if (isset($_SESSION['username'])) {
    header('Location: index.php?route=roulette');
} else {
    header('Location: index.php?route=connexion');
}