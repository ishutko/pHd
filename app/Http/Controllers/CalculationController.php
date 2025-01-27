<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use MathPHP\Exception\BadDataException;
use MathPHP\Exception\IncorrectTypeException;
use MathPHP\Exception\MathException;
use MathPHP\Exception\MatrixException;
use MathPHP\LinearAlgebra\MatrixFactory;

class CalculationController extends Controller
{
    /**
     * Render the calculation form.
     */
    public function showForm()
    {
        return view('calculations.form', [
            'frameworks' => ['CakePHP', 'Yii', 'CodeIgniter'],
        ]);
    }

    /**
     * Handle calculation request.
     */
    public function calculate(Request $request)
    {
        $framework = $request->input('framework');

        // Load configuration for the selected framework
        $config = Config::get("frameworks." . strtolower($framework));

        // Validate inputs
        $validator = Validator::make($request->all(), [
            'noc' => [
                'required',
                'integer',
                'min:' . $config['validation']['noc']['min'],
                'max:' . $config['validation']['noc']['max'],
            ],
            'mbc' => [
                'required',
                'numeric',
                'min:' . $config['validation']['mbc']['min'],
                'max:' . $config['validation']['mbc']['max'],
            ],
            'dit' => [
                'required',
                'numeric',
                'min:' . $config['validation']['dit']['min'],
                'max:' . $config['validation']['dit']['max'],
            ],
            'confidence' => [
                'required',
                'numeric',
                'min:' . $config['validation']['confidence']['min'],
                'max:' . $config['validation']['confidence']['max'],
            ],
        ], [
            'noc.min' => $config['validation']['noc']['error'],
            'noc.max' => $config['validation']['noc']['error'],
            'mbc.min' => $config['validation']['mbc']['error'],
            'mbc.max' => $config['validation']['mbc']['error'],
            'dit.min' => $config['validation']['dit']['error'],
            'dit.max' => $config['validation']['dit']['error'],
            'confidence.min' => $config['validation']['confidence']['error'],
            'confidence.max' => $config['validation']['confidence']['error'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Perform calculations
        $result = $this->performCalculations($request->input(), $config);

        return view('calculations.result', compact('result'));
    }

    /**
     * Generate random values for the form inputs.
     */
    public function generateRandom(Request $request)
    {
        $framework = $request->input('framework');
        $config = Config::get("frameworks." . strtolower($framework));

        $randomData = [
            'noc' => rand($config['generation']['noc']['min'], $config['generation']['noc']['max']),
            'mbc' => mt_rand($config['generation']['mbc']['min'] * 10, $config['generation']['mbc']['max'] * 10) / 10,
            'dit' => mt_rand($config['generation']['dit']['min'] * 10, $config['generation']['dit']['max'] * 10) / 10,
            'confidence' => rand($config['validation']['confidence']['min'], $config['validation']['confidence']['max']),
        ];

        return response()->json($randomData);
    }

    /**
     * Get example data for testing calculations.
     */
    public function getExampleData(Request $request)
    {
        $framework = $request->input('framework');
        $config = Config::get("frameworks." . strtolower($framework));

        if (!isset($config['example_data'])) {
            return response()->json(['error' => 'No example data available for the selected framework.'], 404);
        }

        return response()->json($config['example_data']);
    }

    /**
     * Perform calculations based on user inputs.
     */
    private function performCalculations(array $inputs, array $config)
    {
        $noc = $inputs['noc'];
        $mbc = $inputs['mbc'];
        $dit = $inputs['dit'];
        $confidence = $inputs['confidence'];

        // Box-Cox Transformation
        $lambda = $config['calculation']['box_cox_lambda'];
        $transformedNOC = $this->boxCoxTransform($noc, $lambda);
        $transformedMbC = $this->boxCoxTransform($mbc, $lambda);
        $transformedDIT = $this->boxCoxTransform($dit, $lambda);

        // Формируем нормализованный вектор
        $vector = [$transformedNOC, $transformedMbC, $transformedDIT];

        // Regression Parameters
        $b0 = $config['calculation']['parameters']['b0'];
        $b1 = $config['calculation']['parameters']['b1'];
        $b2 = $config['calculation']['parameters']['b2'];
        $b3 = $config['calculation']['parameters']['b3'];

        // Regression Result
        $regressionResult = $b0 + $b1 * $transformedNOC + $b2 * $transformedMbC + $b3 * $transformedDIT;

        // KLOC Calculation
        $kloc = $this->inverseBoxCox($regressionResult, $lambda);

        // Variance Calculation
        $matrix = $config['calculation']['iteration_matrix'];
        $matrix = $this->iterativeRegularizeMatrix($matrix); // Регуляризация матрицы

        $this->validateDimensions($vector, $matrix);

        $variance = $this->calculateVariance($vector, $matrix);
        $tValue = $config['calculation']['t_value'];

        // Prediction Interval Calculation
        $sigma2 = $config['calculation']['parameters']['sigma'];
        $predictionInterval = $this->calculatePredictionInterval($regressionResult, $variance, $sigma2, $tValue, $lambda);

        // Actual Value
        $actual = $noc * $mbc * $dit;
        // Calculate MMRE and PRED(0.25)
        $metrics = $this->calculateMetrics($actual, $kloc);

        // Confidence Interval Calculation
        $confidenceInterval = $this->calculateConfidenceInterval($regressionResult, $variance, $tValue, $lambda);

        return [
            'framework' => $inputs['framework'],
            'noc' => $noc,
            'mbc' => $mbc,
            'dit' => $dit,
            'confidence' => $confidence,
            'regressionResult' => $regressionResult,
            'predictionInterval' => $predictionInterval,
            'metrics' => $metrics,
            'kloc' => $kloc,
            'confidenceInterval' => $confidenceInterval, // Добавляем Confidence Interval
        ];
    }

    private function calculateVariance(array $vector, array $matrix): float
    {
        // Преобразуем массивы в матрицы MathPHP
        $covarianceMatrix = MatrixFactory::create($matrix);
        $vectorMatrix = MatrixFactory::create([[$vector[0]], [$vector[1]], [$vector[2]]]);
        $transposedVector = $vectorMatrix->transpose();
        $inverseMatrix = $covarianceMatrix->inverse();

        // Вычисляем x^T S_Z^{-1} x
        $result = $transposedVector
            ->multiply($inverseMatrix)
            ->multiply($vectorMatrix);

        return $result->get(0, 0); // Возвращаем скалярное значение
    }

    /**
     * Regularize the covariance matrix iteratively to make it positive definite.
     */
    private function iterativeRegularizeMatrix(array $matrix, float $epsilon = 1e-5, int $maxIterations = 100): array
    {
        for ($i = 0; $i < $maxIterations; $i++) {
            $size = count($matrix);
            for ($j = 0; $j < $size; $j++) {
                $matrix[$j][$j] += $epsilon; // Регуляризация диагональных элементов
            }

            if ($this->isPositiveDefinite($matrix)) {
                return $matrix;
            }

            $epsilon *= 10; // Увеличиваем epsilon на порядок
        }

        throw new InvalidArgumentException("The covariance matrix could not be regularized to become positive definite.");
    }

    private function isPositiveDefinite(array $matrix): bool
    {
        $covarianceMatrix = MatrixFactory::create($matrix);

        foreach ($covarianceMatrix->eigenvalues() as $eigenvalue) {
            if ($eigenvalue <= 0) {
                return false; // Немедленно возвращаем false
            }
        }

        return true;
    }

    /**
     * Calculate Confidence Interval (Lower and Upper bounds).
     *
     * @param float $Zy Результат регрессии.
     * @param float $variance Дисперсия S_Z.
     * @param float $tValue t-критерий.
     * @param float $lambda Параметр Box-Cox.
     * @return array ['lower' => float, 'upper' => float]
     */
    private function calculateConfidenceInterval(float $Zy, float $variance, float $tValue, float $lambda): array
    {
        // Вычисляем нижнюю и верхнюю границы интервала
        $lowerBoundZ = $Zy - $tValue * sqrt($variance);
        $upperBoundZ = $Zy + $tValue * sqrt($variance);

        // Проверяем значения перед обратным преобразованием
        if ($lambda !== 0) {
            if ($lowerBoundZ <= -1 / $lambda) {
                throw new InvalidArgumentException("Lower bound for inverse Box-Cox is invalid: {$lowerBoundZ}");
            }
            if ($upperBoundZ <= -1 / $lambda) {
                throw new InvalidArgumentException("Upper bound for inverse Box-Cox is invalid: {$upperBoundZ}");
            }
        }

        // Преобразуем обратно через Box-Cox
        $lowerBound = $this->inverseBoxCox($lowerBoundZ, $lambda);
        $upperBound = $this->inverseBoxCox($upperBoundZ, $lambda);

        return [
            'lower' => $lowerBound,
            'upper' => $upperBound,
        ];
    }

    /**
     * Validate dimensions of the vector and matrix.
     *
     * @param array $vector Вектор нормализованных значений.
     * @param array $matrix Матрица ковариации.
     * @throws InvalidArgumentException Если размеры вектора и матрицы не соответствуют.
     */
    private function validateDimensions(array $vector, array $matrix): void
    {
        $matrixRows = count($matrix);
        $matrixCols = count($matrix[0]);

        // Проверка квадратности матрицы
        if ($matrixRows !== $matrixCols) {
            throw new InvalidArgumentException("Matrix is not square. Size: {$matrixRows}x{$matrixCols}.");
        }

        // Проверка соответствия длины вектора и размеров матрицы
        if (count($vector) !== $matrixRows) {
            throw new InvalidArgumentException(
                "Vector length (" . count($vector) . ") does not match matrix size ({$matrixRows}x{$matrixCols})."
            );
        }
    }

    private function boxCoxTransform(float $value, float $lambda): float
    {
        return ($lambda === 0) ? log($value) : (pow($value, $lambda) - 1) / $lambda;
    }

    private function inverseBoxCox(float $z, float $lambda): float
    {
        return ($lambda === 0) ? exp($z) : pow($lambda * $z + 1, 1 / $lambda);
    }


    /**
     * Calculate prediction interval using Mahalanobis distance matrix and σ².
     */
    private function calculatePredictionInterval(float $Zy, float $variance, float $sigma2, float $tValue, float $lambda): array
    {
        // Вычисляем нижнюю и верхнюю границы предиктивного интервала
        $lowerBoundZ = $Zy - $tValue * sqrt($variance + $sigma2);
        $upperBoundZ = $Zy + $tValue * sqrt($variance + $sigma2);

        // Преобразуем обратно через Box-Cox
        $lowerBound = $this->inverseBoxCox($lowerBoundZ, $lambda);
        $upperBound = $this->inverseBoxCox($upperBoundZ, $lambda);

        return [
            'lower' => $lowerBound,
            'upper' => $upperBound,
        ];
    }

    /**
     * Calculate MMRE and PRED(0.25).
     *
     * @param float $actual Реальное значение (Actual).
     * @param float $predicted Предсказанное значение (Predicted).
     * @return array ['MMRE' => float, 'PRED(0.25)' => int]
     */
    private function calculateMetrics(float $actual, float $predicted): array
    {
        if ($actual <= 0) {
            throw new InvalidArgumentException("Actual value must be greater than 0. Given: {$actual}");
        }

        $absoluteError = abs($actual - $predicted);
        $relativeError = $absoluteError / $actual;

        $mmre = $relativeError; // Mean Magnitude of Relative Error
        $pred25 = $relativeError <= 0.25 ? 1 : 0; // PRED(0.25)

        return [
            'MMRE' => $mmre,
            'PRED(0.25)' => $pred25,
        ];
    }
}
