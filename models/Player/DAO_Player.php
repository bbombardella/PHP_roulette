<?php

require_once 'models/Player/DTO_player.php';

class DAO_Player
{

    private $bdd;

    public function __construct()
    {
        $db_class = new DB();
        $this->bdd = $db_class->bdd;
    }

    public function getById($player_id)
    {
        try {
            //Création requête + préparation + éxécution
            $query = 'SELECT * FROM player WHERE id=:v_id'; //DEFAULT pour l'id car AUTO_INCREMENT, et pour la date car SYSDATE par défaut
            $prepared = $this->bdd->prepare($query);
            $prepared->execute(array(
                'v_id' => $player_id,
            ));
            $player_data = $prepared->fetch();
        } catch (Exception $e) {
            die("Erreur SQL lors du SELECT id player. {$e->getMessage()}");
        }

        if ($player_data) {
            $player = new DTO_Player($player_data['id'], $player_data['name'], $player_data['password'], $player_data['money']);
        } else {
            $player = null;
        }

        return ($player);
    }

    public function getByUsername($player_username)
    {
        try {
            //Création requête + préparation + éxécution
            $query = 'SELECT * FROM player WHERE name=:v_name'; //DEFAULT pour l'id car AUTO_INCREMENT, et pour la date car SYSDATE par défaut
            $prepared = $this->bdd->prepare($query);
            $prepared->execute(array(
                'v_name' => $player_username,
            ));
            $player_data = $prepared->fetch();
        } catch (Exception $e) {
            die("Erreur SQL lors du SELECT username. {$e->getMessage()}");
        }

        if ($player_data) {
            $player = new DTO_Player($player_data['id'], $player_data['name'], $player_data['password'], $player_data['money']);
        } else {
            $player = null;
        }

        return ($player);
    }

    public function insert($player_username, $player_password)
    {
        $response = array(
            "success" => false,
            "errors" => array(
                "alreadyExists" => false,
            ),
        );

        $is_user_exist = $this->getByUsername($player_username);

        //Si l'utilisateur n'existe pas, je peux faire mon insertion
        if (!$is_user_exist) {
            unset($is_user_exist);
            try {
                //Insertion de informations du nouvel utilisateur
                //Création requête + préparation + éxécution
                $query = 'INSERT INTO player(id,name,password,money) VALUES(DEFAULT,:v_name,:v_password,DEFAULT)'; //DEFAULT pour l'id car AUTO_INCREMENT, et pour la date car SYSDATE par défaut
                $prepared = $this->bdd->prepare($query);
                $prepared->execute(array(
                    'v_name' => $player_username,
                    'v_password' => password_hash($player_password, PASSWORD_BCRYPT),
                ));
            } catch (Exception $e) {
                die("Erreur SQL lors de l'insertion du nouveau player. {$e->getMessage()}");
            }

            $response['success'] = true;
            //Je recupère l'id du nouvel utilisateur et de l'argent
            //car je vais avoir besoin de le stocker dans SESSION
            $player = $this->getByUsername($player_username);
            //J'enregistre les données de l'utilisateur dans le tableau SESSION
            $_SESSION['player'] = $player;
        } else {
            $response['errors']['alreadyExists'] = true;
        }

        return ($response);
    }

    public function updateMoney($player_id, $player_money)
    {
        try {
            //Mise à jour de l'argent du joueur
            //Création requête + préparation + éxécution
            $query = 'UPDATE player SET money=:v_money WHERE id=:v_player_id';
            $prepared = $this->bdd->prepare($query);
            $prepared->execute(array(
                'v_money' => $player_money,
                'v_player_id' => $player_id,
            ));
        } catch (Exception $e) {
            die("Erreur mise à jour de l'argent du joueur. {$e->getMessage()}");
        }
    }

    public function updatePassword($player_id, $player_password)
    {
        try {
            //Mise à jour du mot de passe du joueur déjà "hashé"
            //Création requête + préparation + éxécution
            $query = 'UPDATE player SET password=:v_password WHERE id=:v_player_id';
            $prepared = $this->bdd->prepare($query);
            $prepared->execute(array(
                'v_password' => $player_password,
                'v_player_id' => $player_id,
            ));
        } catch (Exception $e) {
            die("Erreur mise à jour du mot de passe. {$e->getMessage()}");
        }
    }

    public function connection($player_username, $player_password)
    {
        $response = array(
            'success' => false,
            'errors' => array(
                'username' => false,
                'password' => false,
            ),
        );

        //Je récupère les infos du player
        //Création requête + préparation + éxécution
        $player = $this->getByUsername($player_username);

        //Si le player existe
        if ($player != null) {
            //Je compare les hashages BCRYPT des mots de passes
            if (password_verify($player_password, $player->password)) {
                $response['success'] = true;
                //J'enregistre les données de l'utilisateur dans le tableau SESSION
                $_SESSION['player'] = $player;
            } else {
                //Le mot de passe n'est pas le bon
                unset($player);
                $response['errors']['password'] = true;
            }
        } else {
            //L'username n'existe pas
            unset($player);
            $response['errors']['username'] = true;
        }

        return ($response);
    }
}
