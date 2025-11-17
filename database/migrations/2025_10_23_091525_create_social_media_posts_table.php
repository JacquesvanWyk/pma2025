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
        Schema::create('social_media_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sermon_id')->constrained('sermons')->onDelete('cascade');
            $table->enum('platform', ['facebook', 'whatsapp', 'instagram', 'twitter']);
            $table->text('content');
            $table->foreignId('media_file_id')->nullable()->constrained('media_files')->onDelete('set null');
            $table->string('language', 10);
            $table->enum('status', ['draft', 'approved', 'scheduled', 'published', 'failed'])->default('draft');
            $table->timestamp('scheduled_for')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->string('platform_post_id', 255)->nullable();
            $table->json('engagement_data')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['sermon_id', 'platform'], 'idx_sermon_platform');
            $table->index('status', 'idx_status');
            $table->index('scheduled_for', 'idx_scheduled_for');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media_posts');
    }
};
