<?php

namespace App\Services;

use Exception;

class Estimator
{
    private $intercept = -1.63782126445733;
    private $coeffs = [0.96641578624015, 0.96301298247654, -0.46272880601894];
    private $N = 45;
    private $S_Y = 0.0090855323014;
    private $b = [
        [0.27127997, -0.04335353, 0.25063032],
        [-0.04335353, 1.53383441, -0.71641416],
        [0.25063032, -0.71641416, 10.63646880]
    ];
    private $c = [1.4497370, 0.5378432, 0.1425785];
    private $metrics;
    private $confLevel;
    private $a;
    private $se_z;
    private $size;

    /**
     * @throws Exception
     */
    public function __construct(array $vector, float $conf)
    {
        $this->metrics = $vector;
        $this->confLevel = $conf;
        $this->a = array_map('log10', $vector);

        $this->se_z = $this->calculateSeZ($this->a, $this->b, $this->c);
    }

    /**
     * @throws Exception
     */
    private function calculateSeZ($matrixA, $matrixB, $matrixC): array
    {
        // Получаем размеры матриц
        $rowsA = count($matrixA);
        $colsA = count($matrixA[0]);
        $rowsB = count($matrixB);
        $colsB = count($matrixB[0]);
        $rowsC = count($matrixC);
        $colsC = count($matrixC[0]);

        // Проверяем, можно ли перемножить матрицы A и B
        if ($colsA !== $rowsB) {
            throw new Exception('Количество столбцов матрицы A должно быть равно количеству строк матрицы B.');
        }

        // Инициализируем результирующую матрицу для произведения
        $resultMatrix = array_fill(0, $rowsA, array_fill(0, $colsB, 0));

        // Умножение матриц A и B
        for ($i = 0; $i < $rowsA; $i++) {
            for ($j = 0; $j < $colsB; $j++) {
                for ($k = 0; $k < $colsA; $k++) {
                    $resultMatrix[$i][$j] += $matrixA[$i][$k] * $matrixB[$k][$j];
                }
            }
        }

        // Проверяем, можно ли вычесть матрицу C из результата
        if ($rowsC !== $rowsA || $colsC !== $colsB) {
            throw new Exception('Размеры матрицы C должны совпадать с размерами результирующей матрицы.');
        }

        // Вычитание матрицы C из результирующей матрицы
        for ($i = 0; $i < $rowsA; $i++) {
            for ($j = 0; $j < $colsB; $j++) {
                $resultMatrix[$i][$j] -= $matrixC[$i][$j];
            }
        }

        return $resultMatrix;
    }

    public function calculateSize()
    {
        $value = pow(10, $this->intercept) * pow($this->metrics[0], $this->coeffs[0])
            * pow($this->metrics[1], $this->coeffs[1])
            * pow($this->metrics[2], $this->coeffs[2]);

        $this->size = $value;
        return $value;
    }

    public function calculateConf() {
        $conf_val = (stats_stat_tdist_inv(1 - (1 - $this->confLevel) / 2, $this->N - 4) *
            sqrt($this->S_Y * (1.0 / $this->N + $this->se_z)));
        return [
            pow(10, log10($this->size) - $conf_val),
            pow(10, log10($this->size) + $conf_val)
        ];
    }

    public function calculatePred() {
        $pred_val = (stats_stat_tdist_inv(1 - (1 - $this->confLevel) / 2, $this->N - 4) *
            sqrt($this->S_Y * (1 + 1.0 / $this->N + $this->se_z)));
        return [
            pow(10, log10($this->size) - $pred_val),
            pow(10, log10($this->size) + $pred_val)
        ];
    }
}
