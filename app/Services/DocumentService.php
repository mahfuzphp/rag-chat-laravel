<?php

namespace App\Services;


use App\Models\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

class DocumentService
{
    // Maximum file size in bytes (10MB)
    private const MAX_FILE_SIZE = 10 * 1024 * 1024;
    
    // Supported file extensions
    private const SUPPORTED_EXTENSIONS = ['pdf', 'txt'];

    public function __construct(
        private readonly VectorService $vectorService
    ) {}

    public function processDocument(UploadedFile $file, array $metadata = []): Document
    {
        // Validate file before processing
        $this->validateFile($file);

        try {
            // 1. Extract text with error handling
            $text = $this->extractText($file);
            if (empty(trim($text))) {
                throw new \RuntimeException('Extracted text is empty');
            }

            // 2. Split into chunks with meaningful size tracking
            $chunks = $this->splitIntoChunks($text);
            if (empty($chunks)) {
                throw new \RuntimeException('No chunks generated from text');
            }

            // 3. Generate and store vectors with retries
            $vectorIds = $this->vectorService->storeVectors($chunks, $metadata);
            if (empty($vectorIds)) {
                throw new \RuntimeException('Failed to generate vector IDs');
            }

            // 4. Store file with unique name to prevent collisions
            $filePath = $this->storeFile($file);

            // 5. Create document record with enhanced metadata
            return Document::create([
                'name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'metadata' => array_merge($metadata, [
                    'original_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'processed_at' => now(),
                    'chunk_sizes' => array_map('strlen', $chunks)
                ]),
                'chunk_count' => count($chunks),
                'vector_ids' => $vectorIds
            ]);

        } catch (\Exception $e) {
            // Log error with context for debugging
            Log::error('Document processing failed', [
                'file' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Clean up any stored files if processing failed
            if (isset($filePath) && Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
            
            throw new \RuntimeException(
                'Failed to process document: ' . $e->getMessage(),
                0,
                $e
            );
        }
    }

    private function validateFile(UploadedFile $file): void
    {
        if (!$file->isValid()) {
            throw new \RuntimeException('Invalid file upload');
        }

        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new \RuntimeException('File size exceeds maximum limit');
        }

        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, self::SUPPORTED_EXTENSIONS)) {
            throw new \RuntimeException('Unsupported file type');
        }
    }

    private function storeFile(UploadedFile $file): string
    {
        // Generate unique filename using timestamp and random string
        $uniqueName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        // Store in documents directory with unique name
        return $file->storeAs('documents', $uniqueName);
    }

    
    public function getAllDocuments()
    {
        return Document::all();
    }

    public function extractText(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();

        return match ($extension) {
            'pdf' => $this->extractFromPdf($file),
            'txt' => file_get_contents($file->path()),
            default => throw new \Exception('Unsupported file type')
        };
    }

    private function extractFromPdf(UploadedFile $file): string
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($file->path());
        return $pdf->getText();
    }

    public function splitIntoChunks(string $text, int $maxChunkSize = 500): array
    {
        $sentences = preg_split('/(?<=[.!?])\s+/', $text);
        $chunks = [];
        $currentChunk = '';

        foreach ($sentences as $sentence) {
            if (strlen($currentChunk) + strlen($sentence) > $maxChunkSize) {
                $chunks[] = trim($currentChunk);
                $currentChunk = $sentence;
            } else {
                $currentChunk .= ' ' . $sentence;
            }
        }

        if (!empty($currentChunk)) {
            $chunks[] = trim($currentChunk);
        }

        return $chunks;
    }
}
