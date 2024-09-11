<?php

namespace App\Http\Controllers;

use App\Models\CodeIgniterResearchModel;
use App\Models\ResearchModelAbstract;
use App\Models\ResearchModelInterface;
use App\Models\YiiResearchModel;
use Exception;
use Illuminate\Http\Request;
use App\Models\CakePHPResearchModelInterface;

class PredictionController extends Controller
{
    // Цей набір коефіцієнтів використовується для обчислення наближеного значення гамма-функції за допомогою формули,
    // відомої як апроксимація Ланца (Lanczos approximation)
    private $coefficients = [
        1.000000000190015,
        76.18009172947146,
        -86.50532032941677,
        24.01409824083091,
        -1.231739572450155,
        0.001208650973866179,
        -5.395239384953e-6
    ];

    // квантиль Хи квадрат (статичний для модели)
    const KVANTIL = 14.86;

    /**
     * @throws Exception
     */
    public function index(Request $request)
    {
        return view('prediction_form', [
            'models' => array_keys($this->getResearchModels()),
            'random' => $request->exists('model')
                ? $this->generateRandomData($this->getResearchModel($request->get('model')))
                : [],
            'selectedModel' => $request->get('model', ''),
            'kvantil' => self::KVANTIL,
        ]);
    }

    /**
     * @throws Exception
     */
    public function calculate(Request $request)
    {
        // Валідація введених даних
        $request->validate([
            'number_of_classes' => 'required|numeric',
            'average_methods' => 'required|numeric',
            'average_dit' => 'required|numeric',
            'confidence_level' => 'required|numeric|between:0,100',
            'research_model' => 'required|string'
        ]);

        // Отримуємо вибрану модель дослідження
        $modelName = $request->input('research_model');
        $model = $this->getResearchModel($modelName);

        // Вхідні дані з форми
        $X1 = $request->input('number_of_classes');
        $X2 = $request->input('average_methods');
        $X3 = $request->input('average_dit');
        $confidence = $request->input('confidence_level');

        // Отримуємо необхідні дані з вибраної моделі
        $mean = $model::getMean();
        $covMatrixInv = $model::getCovarianceMatrixInverse();
        $coefficients = $model::getRegressionCoefficients();
        $se = $model::getStandardError();

        // Розрахунок відстані Махаланобіса
        $mahalanobisDistance = $this->calculateMahalanobis([$X1, $X2, $X3], $mean, $covMatrixInv);

        // Перевірка, чи не перевищує відстань поріг
        if ($mahalanobisDistance > self::KVANTIL) {
            return redirect()->back()->withErrors('Введені дані відхиляються від допустимого порогу.');
        }

        // Логіка для розрахунку передбаченого значення Y на основі введених даних
        $predictedY = $this->predictY($X1, $X2, $X3, $coefficients);

        // Розрахунок довірчого інтервалу та інтервалу передбачення
        $N = 38;  // Кількість спостережень (з дослідження)
        $alpha = 1 - ($confidence / 100);  // Рівень значущості
        $t_value = $this->getTValue($alpha, $N - 3 - 1);  // t-квантиль

        // Розрахунок відхилення z_X та коваріаційної матриці
        $zX = [$X1 - $mean[0], $X2 - $mean[1], $X3 - $mean[2]];

        // Довірчий інтервал
        $CI = $t_value * $se * sqrt(1 / $N + $this->calculateMahalanobis($zX, [0, 0, 0], $covMatrixInv));

        // Інтервал передбачення
        $PI = $t_value * $se * sqrt(1 + 1 / $N + $this->calculateMahalanobis($zX, [0, 0, 0], $covMatrixInv));

        return view('prediction_result', [
            'predictedY' => $predictedY,
            'mahalanobisDistance' => $mahalanobisDistance,
            'confidenceInterval' => [$predictedY - $CI, $predictedY + $CI],
            'predictionInterval' => [$predictedY - $PI, $predictedY + $PI],
            'model' => $modelName
        ]);
    }

    private function getResearchModels(): array
    {
        return [
            'CakePHP' => CakePHPResearchModelInterface::class,
            'Yii' => YiiResearchModel::class,
            'CodeIgniter' => CodeIgniterResearchModel::class
        ];
    }

    /**
     * @param string|ResearchModelInterface $model
     * @param float $min
     * @param float $max
     * @return array
     */
    public function generateRandomData(string $model, float $min = 0.8, float $max = 1.2): array
    {
        // Отримуємо середні значення для X1, X2, X3 з моделі
        $mean = $model::getMean();

        // Логіка для генерації випадкових даних, які проходять перевірку Махаланобіса
        do {
            // Задаємо діапазон варіації відносно середніх значень
            $X1_min = $mean[0] * $min;  // Мінімальне значення для кількості класів
            $X1_max = $mean[0] * $max;  // Максимальне значення для кількості класів

            $X2_min = $mean[1] * $min;  // Мінімальне значення для середньої кількості методів на клас
            $X2_max = $mean[1] * $max;  // Максимальне значення для середньої кількості методів на клас

            $X3_min = $mean[2] * $min;  // Мінімальне значення для середнього значення DIT
            $X3_max = $mean[2] * $max;  // Максимальне значення для середнього значення DIT

            // Генерація випадкових даних у межах діапазонів
            $randomData = [
                'number_of_classes' => (int)(rand($X1_min * 100, $X1_max * 100) / 100),  // Генеруємо випадкове число з кроком 1
                'average_methods' => rand($X2_min * 100, $X2_max * 100) / 100,    // Генеруємо випадкове число з кроком 0.01
                'average_dit' => rand($X3_min * 100, $X3_max * 100) / 100,        // Генеруємо випадкове число з кроком 0.01
                'confidence_level' => rand(90, 99)  // Випадковий рівень довіри між 90% і 99%
            ];

            // Перевіряємо відстань Махаланобіса
            $mahalanobisDistance = $this->calculateMahalanobis(
                [$randomData['number_of_classes'], $randomData['average_methods'], $randomData['average_dit']],
                $mean,
                $model::getCovarianceMatrixInverse()
            );
        } while ($mahalanobisDistance > self::KVANTIL);  // Якщо відстань більше порога, генеруємо нові дані

        return $randomData;
    }

    /**
     * @param $model
     * @return string|ResearchModelAbstract
     * @throws Exception
     */
    private function getResearchModel($model): string
    {
        $models = $this->getResearchModels();
        if (array_key_exists($model, $models)) {
            return $models[$model];
        }

        throw new Exception('Непідтримувана модель дослідження');
    }

    // Логіка для розрахунку відстані Махаланобіса
    private function calculateMahalanobis($data, $mean, $covMatrixInv)
    {
        $diff = [];
        for ($i = 0; $i < count($data); $i++) {
            $diff[] = $data[$i] - $mean[$i];
        }

        $intermediate = $this->matrixVectorMultiply($covMatrixInv, $diff);

        $result = 0;
        for ($i = 0; $i < count($diff); $i++) {
            $result += $diff[$i] * $intermediate[$i];
        }

        return sqrt($result);
    }

    private function matrixVectorMultiply($matrix, $vector): array
    {
        $result = [];
        foreach ($matrix as $row) {
            $sum = 0;
            for ($i = 0; $i < count($row); $i++) {
                $sum += $row[$i] * $vector[$i];
            }
            $result[] = $sum;
        }
        return $result;
    }

    private function predictY($X1, $X2, $X3, $coefficients): float
    {
        return $coefficients['b0'] + $coefficients['b1'] * $X1 + $coefficients['b2'] * $X2 + $coefficients['b3'] * $X3;
    }

    private function getTValue($alpha, $degrees_of_freedom): float
    {
        // Отримуємо квантиль розподілу Стьюдента для розрахунку t-квантили
        return $this->tStudent($degrees_of_freedom, $alpha / 2);
    }

    // рассчитать плотность вероятности для значения t = 1.5 и числа степеней свободы = 10
    public function tStudent($x, $degreesOfFreedom): float
    {
        $numerator = $this->gamma(($degreesOfFreedom + 1) / 2);
        $denominator = sqrt($degreesOfFreedom * pi()) * $this->gamma($degreesOfFreedom / 2);
        $fraction = pow(1 + ($x * $x) / $degreesOfFreedom, -($degreesOfFreedom + 1) / 2);

        return ($numerator / $denominator) * $fraction;
    }

    // Функция для вычисления гамма-функции
    private function gamma($x): float
    {
        return exp($this->logGamma($x));
    }

    // Функция для вычисления логарифма гамма-функции
    private function logGamma($x): float
    {
        $step = $x + 5.5;
        $step -= ($x + 0.5) * log($step);

        $sum = $this->coefficients[0];
        for ($i = 1; $i < count($this->coefficients); $i++) {
            $sum += $this->coefficients[$i] / ($x + $i);
        }

        return log(2.5066282746310005 * $sum / $x) - $step;
    }
}
