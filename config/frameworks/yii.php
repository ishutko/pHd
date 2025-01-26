<?php

// config/frameworks/yii.php

return [
    'name' => 'Yii',

    // Constants for validation
    'validation' => [
        'noc' => [
            'min' => 1,
            'max' => 500,
            'error' => 'The number of classes (NOC) must be between 1 and 500.'
        ], // Number of classes
        'mbc' => [
            'min' => 0.5,
            'max' => 25,
            'error' => 'The average number of methods per class (MbC) must be between 0.5 and 25.'
        ], // Methods per class
        'dit' => [
            'min' => 0,
            'max' => 12,
            'error' => 'The average depth of inheritance tree (DIT) must be between 0 and 12.'
        ],   // Depth of inheritance tree
        'confidence' => [
            'min' => 50,
            'max' => 99,
            'error' => 'The confidence probability must be between 50% and 99%.'
        ] // Confidence probability in %
    ],

    // Constants for random data generation
    'generation' => [
        'noc' => ['min' => 10, 'max' => 300],
        'mbc' => ['min' => 1, 'max' => 15],
        'dit' => ['min' => 1, 'max' => 8],
    ],

    // Constants for calculations
    'calculation' => [
        'box_cox_lambda' => -0.054599, // Updated value for Box-Cox transformation
        'mahalanobis_threshold' => 14.86, // Chi-square quantile
        'parameters' => [
            'b0' => -4.52586, // Coefficient for univariate Box-Cox (model 7)
            'b1' => 1.37338, // Coefficient for univariate Box-Cox (model 7)
            'b2' => 0.30859, // Coefficient for univariate Box-Cox (model 7)
            'b3' => -0.10751, // Coefficient for univariate Box-Cox (model 7)
            'sigma' => 0.17912 // Updated standard deviation for univariate Box-Cox
        ],
        'iteration_matrix' => [
            [40.258, 22.636, -11.882],
            [22.636, 40.334, -8.213],
            [-11.882, -8.213, 11.332]
        ], // Updated Mahalanobis distance matrix for univariate Box-Cox

        // Metrics calculation thresholds
        'metrics' => [
            'mmre_max' => 0.25, // Maximum acceptable MMRE value
            'pred25_min' => 0.75 // Minimum acceptable PRED(0.25) value
        ],

        't_value' => 2.020 // Значение для 95% доверительного интервала
    ],

    // Example data set for verification
    'example_data' => [
        [
            'Y' => 1.333, 'X1' => 40, 'X2' => 5.57, 'X3' => 2.10, 'SMD' => 3.07, 'SMDz' => 4.71
        ],
        [
            'Y' => 42.543, 'X1' => 401, 'X2' => 7.01, 'X3' => 1.28, 'SMD' => 5.57, 'SMDz' => 4.86
        ],
        [
            'Y' => 55.471, 'X1' => 598, 'X2' => 7.66, 'X3' => 1.27, 'SMD' => 6.75, 'SMDz' => 5.62
        ],
        [
            'Y' => 1.296, 'X1' => 33, 'X2' => 3.97, 'X3' => 1.89, 'SMD' => 0.31, 'SMDz' => 0.44
        ],
        [
            'Y' => 10.175, 'X1' => 174, 'X2' => 5.16, 'X3' => 1.55, 'SMD' => 2.65, 'SMDz' => 1.10
        ],
        [
            'Y' => 1.374, 'X1' => 31, 'X2' => 4.45, 'X3' => 1.93, 'SMD' => 0.47, 'SMDz' => 0.85
        ]
        // Additional rows can be added here as needed
    ]
];
