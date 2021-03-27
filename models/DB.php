<?php

class DB
{

    private $bdd;
    private $hostname;
    private $dbname;
    private $username;
    private $password;

    public function __construct($h = 'localhost:3308', $d = 'roulette', $u = 'root', $p = 'root')
    {
        $this->hostname = $h;
        $this->dbname = $d;
        $this->username = $u;
        $this->password = $p;
        try {
            $this->bdd = new PDO("mysql:host={$this->hostname};dbname={$this->dbname};charset=utf8;", $this->username, $this->password);
        } catch (Exception $e) {
            die("Erreur : {$e->getMessage()}");
        }
    }

    public function __get($attr)
    {
        switch ($attr) {
            case 'bdd':
                return ($this->bdd);
                break;
            case 'hostname':
                return ($this->hostname);
                break;
            case 'dbname':
                return ($this->dbname);
                break;
            case 'username':
                return ($this->username);
                break;
            case 'password':
                return ($this->password);
                break;
        }
    }

    public function __set($attr, $value)
    {
        switch ($attr) {
            case 'bdd':
                $this->dbb = $value;
                break;
            case 'hostname':
                $this->hostname = $value;
                break;
            case 'dbname':
                $this->dbname = $value;
                break;
            case 'username':
                $this->username = $value;
                break;
            case 'password':
                $this->password = $value;
                break;
        }
    }

    public function addGamePlayer($player_id, $game_bet, $game_profit, $player_money)
    {
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

        $this->updatePlayer('balance', $player_id, $player_money);
    }
}
