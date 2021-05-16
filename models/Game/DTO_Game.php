<?php

class DTO_Game
{

    private $id;
    private $player;
    private $date;
    private $bet;
    private $profit;

    public function __construct($id = 0, $player = 0, $date = '', $bet = 0, $profit = 0)
    {
        $this->id = $id;
        $this->player = $player;
        $this->date = $date;
        $this->bet = $bet;
        $this->profit = $profit;
    }

    public function __get($attr)
    {
        switch ($attr) {
            case 'id':
                return ($this->id);
                break;
            case 'player':
                return ($this->player);
                break;
            case 'date':
                return ($this->date);
                break;
            case 'bet':
                return ($this->bet);
                break;
            case 'profit':
                return ($this->profit);
                break;
        }
    }

    public function __set($attr, $value)
    {
        switch ($attr) {
            case 'id':
                $this->id = $value;
                break;
            case 'player':
                $this->player = $value;
                break;
            case 'date':
                $this->date = $value;
                break;
            case 'bet':
                $this->bet = $value;
                break;
            case 'profit':
                $this->profit = $value;
                break;
        }
    }
}
