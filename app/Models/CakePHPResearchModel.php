<?php

namespace App\Models;

class CakePHPResearchModel implements ResearchModel
{
    // Середні значення для X1, X2, X3
    public static function getMean(): array
    {
        return [25, 4.5, 2.0];
    }

    // Обернена коваріаційна матриця для розрахунку відстані Махаланобіса
    public static function getCovarianceMatrixInverse(): array
    {
        return [
            [0.017164, 0.008936, 0.065512],
            [0.008936, 0.355183, -0.00957],
            [0.065512,-0.00957, 2.315295],
        ];
    }

    // Коефіцієнти регресії для моделі
    public static function getRegressionCoefficients(): array
    {
        return [
            'b0' => -4.55626,
            'b1' => 1.09337,
            'b2' => 1.50882,
            'b3' => 0.529486
        ];
    }

    // Стандартна помилка залишків
    public static function getStandardError(): float
    {
        return 0.05444;
    }
}
