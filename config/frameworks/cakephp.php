<?php

// config/frameworks/cakephp.php

return [
    'name' => 'CakePHP',

    // Constants for validation
    'validation' => [
        'noc' => [
            'min' => 1,
            'max' => 500,
            'error' => 'The number of classes (NOC) must be between 1 and 500.'
        ], // Number of classes
        'mbc' => [
            'min' => 0.5,
            'max' => 20,
            'error' => 'The average number of methods per class (MbC) must be between 0.5 and 20.'
        ], // Methods per class
        'dit' => [
            'min' => 0,
            'max' => 10,
            'error' => 'The average depth of inheritance tree (DIT) must be between 0 and 10.'
        ],   // Depth of inheritance tree
        'confidence' => [
            'min' => 50,
            'max' => 99,
            'error' => 'The confidence probability must be between 50% and 99%.'
        ] // Confidence probability in %
    ],

    // Constants for random data generation
    'generation' => [
        'noc' => ['min' => 5, 'max' => 100],
        'mbc' => ['min' => 1, 'max' => 10],
        'dit' => ['min' => 1, 'max' => 5],
    ],

    // Constants for calculations
    'calculation' => [
        'box_cox_lambda' => 0.3, // Updated value for Box-Cox transformation
        'mahalanobis_threshold' => 14.86, // Mahalanobis distance threshold
        'parameters' => [
            'b0' => -4.27326, // Updated coefficient for normalized data
            'b1' => 1.17959,  // Updated coefficient for normalized data
            'b2' => 0.37559,  // Updated coefficient for normalized data
            'b3' => -0.02762, // Updated coefficient for normalized data
            'sigma' => 0.1286  // Updated standard deviation
        ],
        'iteration_matrix' => [
            [7.991, -1.12, -2.60],
            [-1.12, 4.350, 0.049],
            [-2.60, 0.049, 0.594]
        ], // Matrix for Mahalanobis distance

        // Metrics calculation thresholds
        'metrics' => [
            'mmre_max' => 0.25, // Maximum acceptable MMRE value
            'pred25_min' => 0.75 // Minimum acceptable PRED(0.25) value
        ]
    ],

    // Example data set for verification
    'example_data' => [
        [
            'Y' => 45.0, 'X1' => 120, 'X2' => 10.5, 'X3' => 1.2, 'SMD' => 2.5, 'SMDz' => 3.1
        ],
        [
            'Y' => 42.5, 'X1' => 100, 'X2' => 9.8, 'X3' => 1.1, 'SMD' => 2.0, 'SMDz' => 2.8
        ],
        [
            'Y' => 40.0, 'X1' => 110, 'X2' => 10.0, 'X3' => 1.3, 'SMD' => 2.2, 'SMDz' => 2.9
        ],
        [
            'Y' => 38.0, 'X1' => 105, 'X2' => 9.5, 'X3' => 1.0, 'SMD' => 2.1, 'SMDz' => 2.7
        ],
        [
            'Y' => 36.5, 'X1' => 95, 'X2' => 9.0, 'X3' => 1.0, 'SMD' => 1.9, 'SMDz' => 2.5
        ]
    ]
];
