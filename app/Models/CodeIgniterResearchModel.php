<?php

namespace App\Models;

class CodeIgniterResearchModel extends ResearchModelAbstract
{
    // Середні значення для X1, X2, X3
    static $mean = [12.5, 10.0, 1.7];

    // Обернена коваріаційна матриця для розрахунку відстані Махаланобіса
    protected static $covarianceMatrixInverse = [
        [13.516, 79.493, -3.733],
        [79.493, 975.021, -36.849],
        [-3.733, -36.849, 1.500]
    ];

    // Коефіцієнти регресії для моделі
    protected static $regressionCoefficients = [
        'b0' => -2.7220,
        'b1' => 1.207254,
        'b2' => 0.058823,
        'b3' => -0.628765
    ];

    // Стандартна помилка
    protected static $standardError = 0.02722;
}
