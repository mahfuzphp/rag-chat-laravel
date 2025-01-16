<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\FinetuneJob as FinetuneJobModel;

class FinetuneJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly string $jobId,
        private readonly string $modelId,
        private readonly array $parameters
    ) {}

    public function handle(): void
    {
        $job = FinetuneJobModel::find($this->jobId);
        $job->update(['status' => 'running']);

        try {
            // Implementation of model training
            $job->update([
                'status' => 'completed',
                'metrics' => ['loss' => 0.1]
            ]);
        } catch (\Exception $e) {
            $job->update(['status' => 'failed']);
            throw $e;
        }
    }
}
