<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
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

        foreach ($vector as $value) {
            if (!is_numeric($value) || $value <= 0) {
                throw new InvalidArgumentException("The vector contains invalid or non-positive values: " . json_encode($vector));
            }
        }

        // Regression Parameters
        $b0 = $config['calculation']['parameters']['b0'];
        $b1 = $config['calculation']['parameters']['b1'];
        $b2 = $config['calculation']['parameters']['b2'];
        $b3 = $config['calculation']['parameters']['b3'];

        // Regression Result
        $regressionResult = $b0 + $b1 * $transformedNOC + $b2 * $transformedMbC + $b3 * $transformedDIT;

        // KLOC Calculation
        $kloc = $this->inverseBoxCox($regressionResult, $lambda);

        // Calculate MMRE and PRED(0.25)
        $metrics = $this->calculateMetrics($inputs, $regressionResult);

        // Calculate prediction intervals using Mahalanobis distance matrix
        $matrix = $config['calculation']['iteration_matrix'];
        $predictionInterval = $this->calculatePredictionInterval($regressionResult, $matrix);

        if (!$this->isPositiveDefinite($matrix)) {
            throw new InvalidArgumentException("The covariance matrix is not positive definite.");
        }

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
        ];
    }

    private function isPositiveDefinite(array $matrix): bool
    {
        $covarianceMatrix = MatrixFactory::create($matrix);
        $eigenvalues = $covarianceMatrix->eigenvalues(); // Вычисляем собственные значения матрицы

        // Если хотя бы одно собственное значение <= 0, матрица не является положительно определённой
        foreach ($eigenvalues as $eigenvalue) {
            if ($eigenvalue <= 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Обратное преобразование Бокса-Кокса.
     *
     * @param float $z Преобразованное значение.
     * @param float $lambda Параметр λ.
     * @return float Оригинальное значение.
     */
    private function inverseBoxCox($z, $lambda): float
    {
        if ($lambda == 0) {
            return exp($z); // Если λ = 0, используем экспоненциальное преобразование
        } else {
            return pow(($z * $lambda) + 1, 1 / $lambda); // Если λ ≠ 0
        }
    }

    /**
     * Box-Cox Transformation Method
     */
    private function boxCoxTransform($value, $lambda)
    {
        if ($lambda == 0) {
            return log($value);
        }

        return (pow($value, $lambda) - 1) / $lambda;
    }

    /**
     * Calculate prediction interval using Mahalanobis distance matrix
     */
    private function calculatePredictionInterval($regressionResult, $matrix)
    {
        $lowerBound = $regressionResult - sqrt($matrix[0][0]);
        $upperBound = $regressionResult + sqrt($matrix[0][0]);

        return ['lower' => $lowerBound, 'upper' => $upperBound];
    }

    /**
     * Calculate MMRE and PRED(0.25)
     */
    private function calculateMetrics($inputs, $regressionResult)
    {
        $actual = $inputs['noc'] * $inputs['mbc'] * $inputs['dit']; // Example actual size calculation
        $absoluteError = abs($actual - $regressionResult);
        $relativeError = $absoluteError / $actual;

        $mmre = $relativeError; // Mean Magnitude of Relative Error
        $pred25 = $relativeError <= 0.25 ? 1 : 0; // PRED(0.25)

        return [
            'MMRE' => $mmre,
            'PRED(0.25)' => $pred25
        ];
    }
}
