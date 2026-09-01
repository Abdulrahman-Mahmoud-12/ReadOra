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

        'ollama' => [
            'api_key' => env('OLLAMA_API_KEY'),
            'base_url' => env('OLLAMA_BASE_URL', 'http://127.0.0.1:11434/v1/chat/completions'),
            'model' => env('OLLAMA_MODEL', 'llama3.2'),
            'max_tokens' => (int) env('OLLAMA_MAX_TOKENS', 384),
        ],
    ],
];
