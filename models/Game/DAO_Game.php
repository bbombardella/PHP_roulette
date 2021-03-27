<?php

require_once('models/Game/DAO_Game.php');

class DAO_Game {

    private $bdd;

    public function __construct()
    {
        $db_class = new DB();
        $this->bdd = $db_class->bdd;
    }

    public function getById($id) {
        //requête id
        //$game = new DTO_Game($id, $name, $password, $money);
        //return($game);
    }

    public function getAll() {
        //requête all
    }

    public function insert($player_id, $game_bet, $game_profit) {
        try {
            //Insertion de informations sur le jeu
            //Création requête + préparation + éxécution
            $query = 'INSERT INTO game(id,player,date,bet,profit) VALUES (DEFAULT,:v_player,DEFAULT,:v_bet,:v_profit)';
            $prepared = $this->bdd->prepare($query);
            $prepared->execute(array(
                'v_player' => $player_id,
                'v_bet' => $game_bet,
                'v_profit' => $game_profit
            ));
        } catch (Exception $e) {
            die("Erreur SQL insertion ligne de jeu. {$e->getMessage()}");
        }
    }
}