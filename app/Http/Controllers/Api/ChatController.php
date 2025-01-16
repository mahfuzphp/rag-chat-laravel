<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\ChatRequest;
use App\Services\ChatService;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    public function __construct(
        private readonly ChatService $chatService
    ) {}

    public function chat(ChatRequest $request): JsonResponse
    {
        $response = $this->chatService->generateResponse(
            $request->input('message'),
            $request->input('context', [])
        );

        return response()->json([
            'response' => $response,
            'metadata' => [
                'model' => $response->model,
                'tokens' => $response->tokenCount
            ]
        ]);
    }
}
