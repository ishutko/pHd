<?php

return [
    'name' => 'CodeIgniter',

    // Валидация входных данных
    'validation' => [
        'noc' => [
            'min' => 1,
            'max' => 500,
            'error' => 'The number of classes (NOC) must be between 1 and 500.'
        ],
        'mbc' => [
            'min' => 0.5,
            'max' => 25,
            'error' => 'The average number of methods per class (MbC) must be between 0.5 and 25.'
        ],
        'dit' => [
            'min' => 0,
            'max' => 12,
            'error' => 'The average depth of inheritance tree (DIT) must be between 0 and 12.'
        ],
        'confidence' => [
            'min' => 50,
            'max' => 99,
            'error' => 'The confidence probability must be between 50% and 99%.'
        ]
    ],

    // Генерация случайных данных
    'generation' => [
        'noc' => ['min' => 10, 'max' => 300],
        'mbc' => ['min' => 1, 'max' => 15],
        'dit' => ['min' => 1, 'max' => 8],
    ],

    'calculation' => [
        'box_cox_lambda' => 0.4642,
        't_value' => 1.96,
        'parameters' => [
            'b0' => 19.9204,
            'b1' => 0.38757,
            'b2' => 5.67136,
            'b3' => -73.1579,
            'sigma' => 3.42
        ],
        'iteration_matrix' => [
            [215.4, 892.7, -0.88],
            [892.7, 13012.5, -5.22],
            [-0.88, -5.22, 0.0046]
        ]
    ],

    // Примеры тестовых данных из статьи
    'example_data' => [
        [
            'Y' => 42.068, 'X1' => 142, 'X2' => 11.66, 'X3' => 1.33, 'SMD' => 3.50, 'SMDz' => 3.94
        ],
        [
            'Y' => 37.94, 'X1' => 132, 'X2' => 10.89, 'X3' => 1.29, 'SMD' => 2.18, 'SMDz' => 1.76
        ],
        [
            'Y' => 39.073, 'X1' => 138, 'X2' => 10.80, 'X3' => 1.31, 'SMD' => 0.56, 'SMDz' => 0.40
        ],
        [
            'Y' => 40.487, 'X1' => 143, 'X2' => 10.76, 'X3' => 1.33, 'SMD' => 0.49, 'SMDz' => 1.24
        ],
        [
            'Y' => 38.994, 'X1' => 140, 'X2' => 10.72, 'X3' => 1.31, 'SMD' => 0.80, 'SMDz' => 0.24
        ]
    ]
];
