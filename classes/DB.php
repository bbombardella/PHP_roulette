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
                return ($this->dbb);
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

    public function addPlayer($player_username, $player_password)
    {
        $response = array(
            "success" => false,
            "errors" => array(
                "alreadyExists" => false
            )
        );

        try {
            //Vérification utilisateur existant
            //Création requête + préparation + éxécution
            $query = 'SELECT * FROM player WHERE name=:v_name'; //DEFAULT pour l'id car AUTO_INCREMENT, et pour la date car SYSDATE par défaut
            $prepared = $this->bdd->prepare($query);
            $prepared->execute(array(
                'v_name' => $player_username
            ));
            $is_user_exist = $prepared->fetch();
        } catch (Exception $e) {
            die("Erreur SQL lors du SELECT username existant. {$e->getMessage()}");
        }

        //Si l'utilisateur n'existe pas, je peux faire mon insertion
        if (!$is_user_exist) {
            try {
                //Insertion de informations du nouvel utilisateur
                //Création requête + préparation + éxécution
                $query = 'INSERT INTO player(id,name,password,money) VALUES(DEFAULT,:v_name,:v_password,DEFAULT)'; //DEFAULT pour l'id car AUTO_INCREMENT, et pour la date car SYSDATE par défaut
                $prepared = $this->bdd->prepare($query);
                $prepared->execute(array(
                    'v_name' => $player_username,
                    'v_password' => password_hash($player_password, PASSWORD_BCRYPT)
                ));
            } catch (Exception $e) {
                die("Erreur SQL lors de l'insertion du nouveau player. {$e->getMessage()}");
            }

            try {
                $response['success'] = true;
                //Je recupère l'id du nouvel utilisateur et de l'argent
                //car je vais avoir besoin de le stocker dans SESSION
                $query = 'SELECT id, money FROM player WHERE name=?';
                $prepared = $this->bdd->prepare($query);
                $prepared->execute(array($player_username));
                $user_data = $prepared->fetch();
            } catch (Exception $e) {
                die("Erreur SQL lors de la récupération des infos sur le nouveau player. {$e->getMessage()}");
            }
            //J'enregistre les données de l'utilisateur dans le tableau SESSION
            $_SESSION['iduser'] = $user_data['id'];
            $_SESSION['username'] = $player_username;
            $_SESSION['money'] = $user_data['money'];
        } else {
            $response['errors']['alreadyExists'] = true;
        }

        return ($response);
    }

    public function playerConnection($player_username, $player_password)
    {
        $response = array(
            'success' => false,
            'errors' => array(
                'username' => false,
                'password' => false
            )
        );

        //Je récupère les infos du player
        //Création requête + préparation + éxécution
        try {
            $query = 'SELECT * FROM player WHERE name=?';
            $prepared = $this->bdd->prepare($query);
            $prepared->execute(array($player_username));
            $data = $prepared->fetch();
        } catch (Exception $e) {
            die("Erreur SQL lors de la récupération des infos du player. {$e->getMessage()}");
        }

        //Si le player existe
        if ($data != null) {
            //Je compare les hashages BCRYPT des mots de passes
            if (password_verify($player_password, $data['password'])) {
                $response['success'] = true;
                //J'enregistre les données de l'utilisateur dans le tableau SESSION
                $_SESSION['iduser'] = $data['id'];
                $_SESSION['username'] = $data['name'];
                $_SESSION['money'] = $data['money'];

                //-----TODO-----
                //Je redirige vers la page du jeu roulette pour commencer le jeu
                //header('Location: roulette.php');
            } else {
                //Le mot de passe n'est pas le bon
                $response['errors']['password'] = true;
            }
        } else {
            //L'username n'existe pas
            $response['errors']['username'] = true;
        }

        return ($response);
    }

    public function updatePlayer($attr, $player_id, $player_update_data)
    {
        switch ($attr) {
            //Pour la mise à jour
            case 'balance':
                try {
                    //Mise à jour de l'argent du joueur
                    //Création requête + préparation + éxécution
                    $query = 'UPDATE player SET money=v_money WHERE id=v_player_id';
                    $prepared = $this->bdd->prepare($query);
                    $prepared->execute(array(
                        'v_money' => $player_update_data,
                        'v_player_id' => $player_id
                    ));
                } catch (Exception $e) {
                    die("Erreur mise à jour de l'argent du joueur. {$e->getMessage()}");
                }
                break;
            //Pour la mise à jour du mot de passe déjà "hashé"
            case 'password':
                try {
                    //Mise à jour du mot de passe du joueur déjà "hashé"
                    //Création requête + préparation + éxécution
                    $query = 'UPDATE player SET password=v_password WHERE id=v_player_id';
                    $prepared = $this->bdd->prepare($query);
                    $prepared->execute(array(
                        'v_money' => $player_update_data,
                        'v_player_id' => $player_id
                    ));
                } catch (Exception $e) {
                    die("Erreur mise à jour du mot de passe. {$e->getMessage()}");
                }
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