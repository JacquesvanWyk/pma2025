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
        Schema::create('sermon_verses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sermon_id')->constrained('sermons')->onDelete('cascade');
            $table->foreignId('verse_id')->constrained('bible_verses')->onDelete('cascade');
            $table->unsignedInteger('position')->default(0);
            $table->text('context_notes')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['sermon_id', 'verse_id'], 'unique_sermon_verse');
            $table->index(['sermon_id', 'position'], 'idx_sermon_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sermon_verses');
    }
};
