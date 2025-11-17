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
        Schema::create('ai_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sermon_id')->constrained('sermons')->onDelete('cascade');
            $table->enum('type', ['outline', 'illustration', 'application', 'verse', 'general']);
            $table->text('content');
            $table->boolean('applied')->default(false);
            $table->timestamp('applied_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['sermon_id', 'type'], 'idx_sermon_type');
            $table->index('applied', 'idx_applied');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_suggestions');
    }
};
