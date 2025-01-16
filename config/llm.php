<?php

// return [
//     'default_model' => env('LLM_DEFAULT_MODEL', 'mistral-7b'),
//     'api_url' => env('LLM_API_URL', 'http://localhost:8080'),
//     'embedding_model' => env('LLM_EMBEDDING_MODEL', 'all-MiniLM-L6-v2'),

//     'training' => [
//         'batch_size' => env('LLM_TRAINING_BATCH_SIZE', 4),
//         'epochs' => env('LLM_TRAINING_EPOCHS', 3),
//         'learning_rate' => env('LLM_TRAINING_LEARNING_RATE', 2e-5)
//     ]
// ];

return [
    'api_url' => env('LLM_API_URL', 'http://localhost:8080'),
    'embedding_model' => env('LLM_EMBEDDING_MODEL', 'all-MiniLM-L6-v2'),
    'chunk_size' => env('LLM_CHUNK_SIZE', 500)
];
