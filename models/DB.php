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
}
