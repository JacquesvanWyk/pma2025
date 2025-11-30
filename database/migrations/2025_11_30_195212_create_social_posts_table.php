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
        Schema::create('social_posts', function (Blueprint $table) {
            $table->id();
            $table->morphs('postable');
            $table->string('platform'); // facebook, whatsapp
            $table->string('post_id')->nullable(); // External ID from Facebook
            $table->string('status')->default('pending'); // pending, posted, failed
            $table->text('error_message')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();

            $table->index(['postable_type', 'postable_id', 'platform']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_posts');
    }
};
