<?php

namespace App\Models;

interface ResearchModel
{
    // Середні значення для кількості класів (X1), методів на клас (X2), DIT на клас (X3)
    public static function getMean(): array;

    // Обернена коваріаційна матриця для розрахунку відстані Махаланобіса
    public static function getCovarianceMatrixInverse(): array;

    // Коефіцієнти регресії для моделі
    public static function getRegressionCoefficients(): array;

    // Стандартна помилка залишків
    public static function getStandardError(): float;
}
