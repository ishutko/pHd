<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MathPHP\Probability\Distribution\Continuous\StudentT;
use MathPHP\LinearAlgebra\MatrixFactory;

class Estimator extends Model
{
    protected $fillable = ['class', 'methods', 'dit', 'confidence'];

    private $intercept = -1.63782126445733;
    private $coeffs = [0.96641578624015, 0.96301298247654, -0.46272880601894];
    private $N = 45;
    private $S_Y = 0.0090855323014;
    private $ZX = [
        [0.27127997, -0.04335353, 0.25063032],
        [-0.04335353, 1.53383441, -0.71641416],
        [0.25063032, -0.71641416, 10.63646880]
    ];
    private $Z_MEAN = [1.4497370, 0.5378432, 0.1425785];

    public function calculateEstimations()
    {
        $metrics = [$this->class, $this->methods, $this->dit];
        $confLevel = $this->confidence;

        // Создаем массив zx как массив массивов для подготовки к матричным операциям
        $zx = array_map(function ($metric, $mean) {
            return [log10($metric) - $mean];
        }, $metrics, $this->Z_MEAN);

        // Создаем матрицы для вычислений
        $zxMatrix = MatrixFactory::create($zx);
        $ZXMatrix = MatrixFactory::create($this->ZX);
        $product = $zxMatrix->transpose()->multiply($ZXMatrix)->multiply($zxMatrix);
        $se_z = $product->get(0, 0);

        // Расчет размера проекта
        $size = pow(10, $this->intercept) * pow($metrics[0], $this->coeffs[0]) * pow($metrics[1], $this->coeffs[1]) * pow($metrics[2], $this->coeffs[2]);
        $df = $this->N - 4;
        $t_value = $this->tDistribution($df, $confLevel);

        // Расчет доверительного и предсказательного интервалов
        $conf_val = $t_value * sqrt($this->S_Y * (1.0 / $this->N + $se_z));
        $conf_interval = [
            pow(10, log10($size) - $conf_val),
            pow(10, log10($size) + $conf_val)
        ];

        $pred_val = $t_value * sqrt($this->S_Y * (1 + 1.0 / $this->N + $se_z));
        $pred_interval = [
            pow(10, log10($size) - $pred_val),
            pow(10, log10($size) + $pred_val)
        ];

        return [
            'size' => $size,
            'confidence_interval' => $conf_interval,
            'prediction_interval' => $pred_interval
        ];
    }

    private function tDistribution($df, $confidenceLevel)
    {
        $t = new StudentT($df);
        return $t->inverse(1 - (1 - $confidenceLevel) / 2);
    }
}
