<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentUploadRequest;
use App\Services\DocumentService;
use Illuminate\Http\JsonResponse;

class DocumentController extends Controller
{
    public function __construct(
        private readonly DocumentService $documentService
    ) {}

    public function upload(DocumentUploadRequest $request): JsonResponse
    {
        // Process and store document for RAG
        $result = $this->documentService->processDocument(
            $request->file('document'),
            $request->input('metadata', [])
        );

        return response()->json([
            'message' => 'Document processed successfully',
            'document_id' => $result->id
        ]);
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'documents' => $this->documentService->getAllDocuments()
        ]);
    }
}

