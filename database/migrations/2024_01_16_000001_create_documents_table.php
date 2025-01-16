<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_path');
            $table->json('metadata')->nullable();
            $table->integer('chunk_count')->default(0);
            $table->json('vector_ids')->nullable();
            $table->timestamps();
        });
    }
};
