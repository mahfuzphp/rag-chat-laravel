<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentUploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'document' => 'required|file|mimes:pdf,doc,docx,txt|max:10240',
            'metadata' => 'array'
        ];
    }
}
