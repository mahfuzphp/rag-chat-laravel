<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VectorService
{
    private const COLLECTION_NAME = 'documents';
    private string $qdrantUrl;
    private const EMBEDDING_API_URL = 'http://localhost:8080/embeddings';

    public function __construct()
    {
        $this->qdrantUrl = sprintf(
            'http://%s:%s',
            config('vector.host', 'localhost'),
            config('vector.port', 6333)
        );
    }

    public function search(string $query, int $limit = 3): array
    {
        $embedding = $this->generateEmbedding($query);

        $response = Http::post($this->qdrantUrl . '/collections/' . self::COLLECTION_NAME . '/points/search', [
            'vector' => $embedding,
            'limit' => $limit,
            'with_payload' => true
        ]);

        return $response->json('result', []);
    }

    public function generateEmbedding(string $text): array
    {
        try {
            $response = Http::post(self::EMBEDDING_API_URL, [
                'input' => $text,
                'model' => config('llm.embedding_model'),
            ]);

            if (!$response->successful()) {
                throw new \RuntimeException('Embedding generation failed: ' . $response->body());
            }

            $embedding = $response->json('data.0.embedding');

            if (empty($embedding)) {
                throw new \RuntimeException('No embedding returned');
            }

            return $embedding;
        } catch (\Exception $e) {
            Log::error('Embedding generation failed: ' . $e->getMessage());
            throw new \RuntimeException('Failed to generate embedding', 0, $e);
        }
    }

    public function batchGenerateEmbeddings(array $texts): array
    {
        try {
            $response = Http::post(self::EMBEDDING_API_URL, [
                'input' => $texts,
                'model' => config('llm.embedding_model'),
            ]);

            return collect($response->json('data'))
                ->pluck('embedding')
                ->all();
        } catch (\Exception $e) {
            Log::error('Batch embedding generation failed: ' . $e->getMessage());
            throw new \RuntimeException('Failed to generate embeddings', 0, $e);
        }
    }

    public function storeVectors(array $chunks, array $metadata = []): array
    {
        $embeddings = $this->batchGenerateEmbeddings($chunks);

        $points = [];
        foreach ($embeddings as $index => $embedding) {
            $points[] = [
                'id' => uniqid('vec_'),
                'vector' => $embedding,
                'payload' => [
                    'text' => $chunks[$index],
                    'metadata' => $metadata
                ]
            ];
        }

        $response = Http::post($this->qdrantUrl . '/collections/' . self::COLLECTION_NAME . '/points', [
            'points' => $points
        ]);

        if (!$response->successful()) {
            throw new \RuntimeException('Failed to store vectors: ' . $response->body());
        }

        return array_column($points, 'id');
    }
}
