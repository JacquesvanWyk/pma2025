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
        Schema::create('ai_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('sermon_id')->nullable()->constrained('sermons')->onDelete('cascade');
            $table->string('session_id', 100);
            $table->string('provider', 50);
            $table->string('model', 100);
            $table->string('title')->nullable();
            $table->unsignedInteger('total_tokens_used')->default(0);
            $table->decimal('estimated_cost', 10, 4)->default(0.0000);
            $table->timestamps();
            $table->timestamp('ended_at')->nullable();

            $table->index(['user_id', 'session_id']);
            $table->index('sermon_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_conversations');
    }
};
