<?php

namespace lucidtaz\reversi\gamelogic;

use lucidtaz\minimax\game\Player as PlayerInterface;

class Player implements PlayerInterface
{
    private $sign;

    public static function NONE(): Player
    {
        $result = new Player;
        $result->sign = ' ';
        return $result;
    }

    public static function BLUE(): Player
    {
        $result = new Player;
        $result->sign = 'B';
        return $result;
    }

    public static function RED(): Player
    {
        $result = new Player;
        $result->sign = 'R';
        return $result;
    }

    /**
     * @param Player $other
     */
    public function equals(PlayerInterface $other): bool
    {
        return $other->sign == $this->sign;
    }

    /**
     * @param Player $other
     */
    public function isFriendsWith(PlayerInterface $other): bool
    {
        return $this->equals($other);
    }

    public function __toString(): string
    {
        return $this->sign;
    }
}
