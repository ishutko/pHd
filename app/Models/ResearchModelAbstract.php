<?php

namespace App\Models;

abstract class ResearchModelAbstract implements ResearchModelInterface
{
    static $mean = [];
    protected static $covarianceMatrixInverse = [];
    protected static $regressionCoefficients = [];
    protected static $standardError = 0;

    public static function getMean(): array
    {
        return static::$mean;
    }

    // Метод для отримання оберненої коваріаційної матриці
    public static function getCovarianceMatrixInverse(): array
    {
        return static::$covarianceMatrixInverse;
    }

    // Метод для отримання коефіцієнтів регресії
    public static function getRegressionCoefficients(): array
    {
        return static::$regressionCoefficients;
    }

    // Метод для отримання стандартної помилки
    public static function getStandardError(): float
    {
        return static::$standardError;
    }}
