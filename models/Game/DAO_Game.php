<?php

require_once('models/DB.php');
require_once('models/Game/DAO_Game.php');

class DAO_Game {

    private $bdd;

    public function __construct()
    {
        $this->bdd = new DB();
    }

    public function getById($id) {
        //requête id
        //$game = new DTO_Game($id, $name, $password, $money);
        //return($game);
    }

    public function getAll() {
        //requête all
    }

    public function insert() {
        //insertion
    }
}