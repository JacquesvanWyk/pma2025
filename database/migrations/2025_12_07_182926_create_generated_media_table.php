<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('generated_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // image, video, music
            $table->string('provider')->default('kie');
            $table->string('model');
            $table->string('task_id')->nullable();
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->text('prompt');
            $table->json('settings')->nullable();
            $table->string('file_path')->nullable();
            $table->string('remote_url')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->decimal('credits_used', 10, 4)->default(0);
            $table->decimal('cost_usd', 10, 4)->default(0);
            $table->integer('duration_seconds')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'type', 'status']);
            $table->index('task_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generated_media');
    }
};
