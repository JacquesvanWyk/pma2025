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
        Schema::create('api_usage_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('provider'); // gemini, nanobanana
            $table->string('service'); // image-generation, text-generation
            $table->string('model')->nullable();
            $table->string('action'); // generate, request, etc.
            $table->json('request_data')->nullable(); // prompt length, etc.
            $table->json('response_data')->nullable(); // rate limit info, tokens, etc.
            $table->integer('tokens_used')->nullable();
            $table->string('status'); // success, rate_limited, error
            $table->text('error_message')->nullable();
            $table->json('rate_limit_info')->nullable(); // limit, remaining, resets_at
            $table->timestamps();

            $table->index(['provider', 'service', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_usage_stats');
    }
};
