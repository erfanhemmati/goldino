<?php

return [
    'tiers' => [
        ['up_to' => 1.0,  'percentage' => 2.0],
        ['up_to' => 10.0, 'percentage' => 1.5],
        ['up_to' => null, 'percentage' => 1.0],
    ],

    'min' => env('FEES_MIN', 500000),
    'max' => env('FEES_MAX', 50000000),
];
