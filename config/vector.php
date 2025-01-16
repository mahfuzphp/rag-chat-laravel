<?php

return [
    'host' => env('QDRANT_HOST', 'localhost'),
    'port' => env('QDRANT_PORT', 6333),
    'collection' => env('QDRANT_COLLECTION', 'documents'),
    'dimensions' => env('VECTOR_DIMENSIONS', 384), // Depends on your embedding model
];
