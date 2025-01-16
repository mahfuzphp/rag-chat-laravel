<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'name',
        'file_path',
        'metadata',
        'chunk_count',
        'vector_ids'
    ];

    protected $casts = [
        'metadata' => 'array',
        'vector_ids' => 'array'
    ];
}
