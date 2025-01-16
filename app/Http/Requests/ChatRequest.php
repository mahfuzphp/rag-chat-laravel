<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'message' => 'required|string|max:1000',
            'context' => 'array'
        ];
    }
}
