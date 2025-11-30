<?php

namespace App\Jobs;

use App\Models\SocialPost;
use App\Services\FacebookService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PostToFacebookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        public Model $model,
        public string $imageUrl,
        public string $caption
    ) {}

    public function handle(FacebookService $facebookService): void
    {
        $socialPost = SocialPost::create([
            'postable_type' => get_class($this->model),
            'postable_id' => $this->model->id,
            'platform' => 'facebook',
            'status' => 'pending',
        ]);

        $result = $facebookService->postPhoto($this->imageUrl, $this->caption);

        if ($result && $result['success']) {
            $socialPost->update([
                'status' => 'posted',
                'post_id' => $result['post_id'],
                'posted_at' => now(),
            ]);

            Log::info('Facebook post successful', [
                'model' => get_class($this->model),
                'model_id' => $this->model->id,
                'post_id' => $result['post_id'],
            ]);
        } else {
            $socialPost->update([
                'status' => 'failed',
                'error_message' => $result['error'] ?? 'Unknown error',
            ]);

            Log::error('Facebook post failed', [
                'model' => get_class($this->model),
                'model_id' => $this->model->id,
                'error' => $result['error'] ?? 'Unknown error',
            ]);
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('PostToFacebookJob failed permanently', [
            'model' => get_class($this->model),
            'model_id' => $this->model->id,
            'exception' => $exception->getMessage(),
        ]);

        SocialPost::where('postable_type', get_class($this->model))
            ->where('postable_id', $this->model->id)
            ->where('platform', 'facebook')
            ->where('status', 'pending')
            ->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
            ]);
    }
}
