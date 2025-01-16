<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Http\UploadedFile;
use App\Services\VectorService;

class ChatService
{
    public function __construct(
        private readonly VectorService $vectorService
    ) {}

    public function processDocument(UploadedFile $file, array $metadata = []): Document
    {
        // Extract text from document
        $text = $this->extractText($file);

        // Split into chunks
        $chunks = $this->splitIntoChunks($text);

        // Create embeddings and store in vector DB
        $vectors = $this->vectorService->storeVectors($chunks);

        // Store document metadata
        return Document::create([
            'filename' => $file->getClientOriginalName(),
            'metadata' => $metadata,
            'chunk_count' => count($chunks),
            'vector_ids' => $vectors
        ]);
    }

    private function extractText(UploadedFile $file): string
    {
        // Implementation depends on file type (PDF, DOCX, etc.)
    }

    private function splitIntoChunks(string $text): array
    {
        // Implement chunking logic
        return array_filter(explode("\n\n", $text));
    }
}
