<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class FinetuneJob extends Model
{
    use HasUuids;

    protected $fillable = [
        'model_id',
        'status',
        'parameters',
        'metrics'
    ];

    protected $casts = [
        'parameters' => 'array',
        'metrics' => 'array'
    ];
}
