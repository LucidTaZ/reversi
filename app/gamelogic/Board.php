<?php

namespace lucidtaz\reversi\gamelogic;

use Generator;

/**
 * 8x8 Reversi board
 * It simply tracks which players own which cells.
 */
class Board
{
    /**
     * @var array Simple 8x8 array of Player objects to denote who owns it.
     */
    private $cells;

    public const SIZE_X = 8;
    public const SIZE_Y = 8;

    public function __construct()
    {
        $this->initializeEmptyFields();
        $this->placeStartingTokens();
    }

    private function initializeEmptyFields(): void
    {
        $none = Player::NONE();
        for ($row = 0; $row < self::SIZE_Y; $row++) {
            for ($column = 0; $column < self::SIZE_X; $column++) {
                $this->cells[$row][$column] = $none;
            }
        }
    }

    private function placeStartingTokens(): void
    {
        $this->cells[3][3] = Player::BLUE();
        $this->cells[3][4] = Player::RED();
        $this->cells[4][3] = Player::RED();
        $this->cells[4][4] = Player::BLUE();
    }

    public function isWithinBounds($row, $column): bool
    {
        return $row >= 0 && $row < self::SIZE_Y && $column >= 0 && $column < self::SIZE_X;
    }

    /**
     * Tells which player owns the specified field
     * Player::NONE() in case the field is not owned.
     */
    public function getField($row, $column): Player
    {
        return $this->cells[$row][$column];
    }

    public function fillField(int $row, int $column, Player $owner): void
    {
        $this->cells[$row][$column] = $owner;
    }

    public function countOwnedCells(Player $player): int
    {
        $ownedCells = 0;
        foreach ($this->cells as $rowValues) {
            foreach ($rowValues as $fieldValue) {
                if ($fieldValue->equals($player)) {
                    $ownedCells++;
                }
            }
        }
        return $ownedCells;
    }

    public function isOwnedBy(int $row, int $column, Player $player): bool
    {
        if (!$this->isWithinBounds($row, $column)) {
            return false;
        }

        $neighbordField = $this->getField($row, $column);
        return $neighbordField->equals($player);
    }

    /**
     * @return Generator Each element is an (x, y) tuple
     */
    public function getEmptyFields(): Generator
    {
        foreach ($this->cells as $row => $rowValues) {
            foreach ($rowValues as $col => $fieldValue) {
                if ($fieldValue->equals(Player::NONE())) {
                    yield [$row, $col];
                }
            }
        }
    }
}
