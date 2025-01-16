<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinetuneRequest extends FormRequest {
    public function rules(): array {
        return [
            'model_id' => 'required|string',
            'training_data' => 'required|array',
            'parameters' => 'array'
        ];
    }
}