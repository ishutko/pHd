<?php

namespace App\Models;

class YiiResearchModel extends ResearchModelAbstract
{
    // Середні значення для X1, X2, X3
    static $mean = [40, 6.5, 1.9];

    // Обернена коваріаційна матриця для розрахунку відстані Махаланобіса
    protected static $covarianceMatrixInverse = [
        [74.222, 28.777, -10.760],
        [28.777, 37.154, -5.262],
        [-10.760, -5.262, 4.563]
    ];

    // Коефіцієнти регресії для моделі
    protected static $regressionCoefficients = [
        'b0' => -3.94744,
        'b1' => 1.02437,
        'b2' => 0.36521,
        'b3' => -0.14416
    ];

    // Стандартна помилка
    protected static $standardError = 0.16918;
}
