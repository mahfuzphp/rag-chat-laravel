<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LLMService
{
    public function generate(string $message, array $context): string
    {
        $prompt = $this->buildPrompt($message, $context);

        try {
            $response = Http::post(config('llm.api_url'), [
                'prompt' => $prompt,
                'model' => config('llm.model', 'mistral-7b'),
                'temperature' => 0.7,
                'max_tokens' => 500
            ]);

            if (!$response->successful()) {
                throw new \RuntimeException('LLM API call failed: ' . $response->body());
            }

            return $response->json('text') ?? throw new \RuntimeException('No response text returned');
        } catch (\Exception $e) {
            Log::error('LLM generation failed: ' . $e->getMessage());
            throw new \RuntimeException('Failed to generate response', 0, $e);
        }
    }

    private function buildPrompt(string $message, array $context): string
    {
        return "Context:\n" .
            implode("\n\n", $context) .
            "\n\nUser: $message\nAssistant:";
    }
}
