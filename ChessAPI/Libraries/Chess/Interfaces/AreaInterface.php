<?php


namespace Libraries\Chess\Interfaces;

use Libraries\Chess\ChessPiece;


interface AreaInterface
{


    /**
     * Удалить фигуру по заддоному положению на поле
     *
     * @param int $x Положение фигуры по X
     * @param int $y Положение фигуры по Y
     *
     */
    function deletePiece($x, $y);


    /**
     * Перемещение фигуры на поле (Никаких проверок на позицию не проводиться)
     *
     * @param int $oldX Положение фигуры по X
     * @param int $oldY Положение фигуры по Y
     * @param int $newX Новое положение фигуры по X
     * @param int $newY Новое положение фигуры по Y
     */
    function movePiece($oldX, $oldY, $newX, $newY);


    /**
     * Может ли вражеская фигура сходить на данную клетку
     *
     * @param int $oldX Старая позиция по X
     * @param int $oldY Старая позиция по Y
     * @param int $x Положение фигуры по X
     * @param int $y Положение фигуры по Y
     * @param int $player Дружелюбный игрок
     * @return bool
     */
     function checkWhoGoCage($oldX, $oldY, $x, $y, $player):bool;


    /**
     * Проверяет закончилась ли игра или наступил Шах
     *
     * @param int $player С стороны какого игрока проверять
     * @param bool $Checkmate Проверять ли мат в игре
     */
     function checkEnd($player, $Checkmate=False);


    /**
     * Возвращает фигуру на данной позиции
     *
     * @param int $x Положение фигуры по X
     * @param int $y Положение фигуры по Y
     *
     * @return ?ChessPiece
     */
    public function getFigure($x,$y): ?ChessPiece;
}