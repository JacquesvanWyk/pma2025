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
        Schema::create('sermon_topic_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sermon_id')->constrained('sermons')->onDelete('cascade');
            $table->foreignId('topic_id')->constrained('sermon_topics')->onDelete('cascade');
            $table->decimal('relevance_score', 3, 2)->default(1.00);
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['sermon_id', 'topic_id'], 'unique_sermon_topic');
            $table->index(['topic_id', 'relevance_score'], 'idx_topic_relevance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sermon_topic_mappings');
    }
};
