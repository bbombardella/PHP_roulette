<?php

require_once 'models/Game/DTO_Game.php';

class DAO_Game
{

    private $bdd;

    public function __construct()
    {
        $db_class = new DB();
        $this->bdd = $db_class->bdd;
    }

    public function getById($game_id)
    {
        //requête id
        try {
            //Création requête + préparation + éxécution
            $query = 'SELECT * FROM game WHERE id=:v_id'; //DEFAULT pour l'id car AUTO_INCREMENT, et pour la date car SYSDATE par défaut
            $prepared = $this->bdd->prepare($query);
            $prepared->execute(array(
                'v_id' => $game_id,
            ));
            $game_data = $prepared->fetch();
        } catch (Exception $e) {
            die("Erreur SQL lors du SELECT id game. {$e->getMessage()}");
        }

        if ($game_data) {
            $game = new DTO_Game($game_data['id'], $game_data['player'], $game_data['date'], $game_data['bet'], $game_data['profit']);
        } else {
            $game = null;
        }

        return ($game);
    }

    public function insert($player_id, $game_bet, $game_profit)
    {
        try {
            //Insertion de informations sur le jeu
            //Création requête + préparation + éxécution
            $query = 'INSERT INTO game(id,player,date,bet,profit) VALUES (DEFAULT,:v_player,DEFAULT,:v_bet,:v_profit)';
            $prepared = $this->bdd->prepare($query);
            $prepared->execute(array(
                'v_player' => $player_id,
                'v_bet' => $game_bet,
                'v_profit' => $game_profit,
            ));
        } catch (Exception $e) {
            die("Erreur SQL insertion ligne de jeu. {$e->getMessage()}");
        }
    }
}
