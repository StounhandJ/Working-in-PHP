<?php


namespace Libraries\Chess\Interfaces;


interface ChessPieceInterface
{


    /**
     * Попытка на движение фигуры по полю
     *
     * @param int $x Новое положение фигуры по X
     * @param int $y Новое положение фигуры по Y
     *
     * @return boolean
     */
    public function move($x, $y): bool;

    /**
     * Дополнительная проверка для фигуры за другой фигурой
     *
     * @param int $startX Начальная X
     * @param int $startY Начальная Y
     * @param int $endX Конечная X
     * @param int $endY Конечная Y
     * @param int $x Новое положение фигуры по X
     * @param int $y Новое положение фигуры по Y
     *
     * @return boolean
     */
    public function checkRoadTwo($startX, $startY, $endX, $endY, $x, $y):bool;

    /**
     * Проверка на фигуры на пути
     *
     * @return boolean
     */
    public function checkRoad():bool;

   /**
     * Проверка вражеской фигуры на данной позиции
     *
     * @param int $x Положение проверки по X
     * @param int $y Положение проверки по Y
     * @return boolean
     */
    public function checkEnemyFigure($x, $y):bool;

    /**
     * Проверка дружеской фигуры на данной позиции
     *
     * @param int $x Положение проверки по X
     * @param int $y Положение проверки по Y
     * @return boolean
     */
    public function checkFriendlyFigure($x, $y):bool;

    /**
     * Проверка будет ли шах в следующем ходе
     *
     * @return boolean
     */
    public function checkShahGame():bool;

    /**
     * Возвращает все возможные ходы для фигуры по полю
     *
     * @return array
     */
    public function getPossibleMoves():array;

    /**
     * Проверка хода для фигурыы
     *
     * @param bool $CheckShah Нужно ли проверять Шах в следующем ходе(False в основном при проврки конца игры)
     * @return boolean
     */
    public function check($CheckShah=True): bool;
}