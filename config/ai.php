<?php

return [
    'provider' => env('AI_PROVIDER', 'nvidia'),

    'providers' => [
        'nvidia' => [
            'api_key' => env('NVIDIA_NIM_API_KEY'),
            'base_url' => env('NVIDIA_NIM_BASE_URL'),
            'model' => env('NVIDIA_NIM_MODEL'),
        ],

        'openrouter' => [
            'api_key' => env('OPENROUTER_API_KEY'),
            'base_url' => env('OPENROUTER_BASE_URL'),
            'model' => env('OPENROUTER_MODEL'),
        ],
    ],
];
