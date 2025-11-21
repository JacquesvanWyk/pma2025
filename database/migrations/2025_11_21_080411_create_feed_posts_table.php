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
        Schema::create('feed_posts', function (Blueprint $table) {
            $table->id();
            $table->morphs('author'); // author_type, author_id (Individual, Fellowship, Ministry)
            $table->enum('type', ['update', 'prayer', 'testimony', 'resource', 'event']);
            $table->string('title')->nullable();
            $table->text('content');
            $table->json('images')->nullable();
            $table->json('attachments')->nullable();
            $table->string('video_url')->nullable();
            $table->timestamp('answered_at')->nullable(); // For prayer requests
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_posts');
    }
};
