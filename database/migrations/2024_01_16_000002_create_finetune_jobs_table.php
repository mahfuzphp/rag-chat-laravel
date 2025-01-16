<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('finetune_jobs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('model_id');
            $table->string('status');
            $table->json('parameters');
            $table->json('metrics')->nullable();
            $table->timestamps();
        });
    }
};
