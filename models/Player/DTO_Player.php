<?php

class DTO_Player {

    private $id;
    private $name;
    private $password;
    private $money;

    public function __construct($id = 0, $name = '', $password = '', $money = 0)
    {
        $this->id = $id;
        $this->name = $name;
        $this->password = $password;
        $this->money = $money;
    }

    public function __toString()
    {
        return("Player {$this->id} -- {$this->name} ayant pour solde {$this->money}€");
    }

    public function __get($attr) {
        switch($attr) {
            case 'id':
                return($this->id);
                break;
            case 'name':
                return($this->name);
                break;
            case 'password':
                return($this->password);
                break;
            case 'money':
                return($this->money);
                break;
        }
    }

    public function __set($attr, $value) {
        switch($attr) {
            case 'id':
                $this->id = $value;
                break;
            case 'name':
                $this->name = $value;
                break;
            case 'password':
                $this->password = $value;
                break;
            case 'money':
                $this->money = $value;
                break;
        }
    }

}