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
        Schema::create('sermon_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sermon_id')->constrained('sermons')->onDelete('cascade');
            $table->string('language', 10);
            $table->string('title', 255);
            $table->string('subtitle', 255)->nullable();
            $table->longText('content');
            $table->text('excerpt')->nullable();
            $table->boolean('translated_by_ai')->default(true);
            $table->foreignId('verified_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->unique(['sermon_id', 'language'], 'unique_sermon_language');
            $table->index('language', 'idx_language');

            // Only create fulltext index on MySQL
            if (DB::connection()->getDriverName() === 'mysql') {
                $table->fullText(['title', 'subtitle', 'content', 'excerpt'], 'idx_fulltext_search');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sermon_translations');
    }
};
