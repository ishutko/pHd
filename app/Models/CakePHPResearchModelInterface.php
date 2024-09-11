<?php

namespace App\Models;

class CakePHPResearchModelInterface extends ResearchModelAbstract
{
    // Середні значення для X1, X2, X3
    static $mean = [25, 4.5, 2.0];

    // Обернена коваріаційна матриця для розрахунку відстані Махаланобіса
    protected static $covarianceMatrixInverse = [
        [0.017164, 0.008936, 0.065512],
        [0.008936, 0.355183, -0.00957],
        [0.065512,-0.00957, 2.315295],
    ];

    // Коефіцієнти регресії для моделі
    protected static $regressionCoefficients = [
        'b0' => -4.55626,
        'b1' => 1.09337,
        'b2' => 1.50882,
        'b3' => 0.529486
    ];

    // Стандартна помилка залишків
    protected static $standardError = 0.05174;
}
