<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bible_verses', function (Blueprint $table) {
            $table->id();
            $table->string('book', 50);
            $table->unsignedInteger('chapter');
            $table->unsignedInteger('verse');
            $table->string('translation', 20);
            $table->string('language', 10);
            $table->text('text');
            $table->string('formatted_reference', 100);
            $table->string('api_source', 50)->default('api.bible');
            $table->timestamps();

            $table->unique(['book', 'chapter', 'verse', 'translation']);
            $table->index(['book', 'chapter', 'verse']);
            $table->index(['translation', 'language']);

            // Only create fulltext index on MySQL
            if (DB::connection()->getDriverName() === 'mysql') {
                $table->fullText('text');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bible_verses');
    }
};
