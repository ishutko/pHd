<?php

// config/frameworks/codeigniter.php

return [
    'name' => 'CodeIgniter',

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
        'box_cox_lambda' => 0.46420, // Updated value for univariate Box-Cox transformation
        'mahalanobis_threshold' => 14.86, // Chi-square quantile
        'parameters' => [
            'b0' => -9.02530, // Coefficient for univariate Box-Cox transformation
            'b1' => 1.11051, // Coefficient for normalized data
            'b2' => -0.00083422, // Coefficient for normalized data
            'b3' => -11.6498, // Coefficient for normalized data
            'sigma' => 0.27344 // Updated standard deviation for univariate Box-Cox
        ],
        'iteration_matrix' => [
            [320.282, 1035.442, -0.79465],
            [1035.442, 16281.89, -6.3029],
            [-0.79465, -6.3029, 0.003834]
        ], // Matrix for Mahalanobis distance for univariate Box-Cox

        // Metrics calculation thresholds
        'metrics' => [
            'mmre_max' => 0.25, // Maximum acceptable MMRE value
            'pred25_min' => 0.75 // Minimum acceptable PRED(0.25) value
        ]
    ],

    // Example data set for verification (added from extracted table)
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
        // Additional rows can be added here as needed
    ]
];
