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
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('edition')->nullable();
            $table->text('description')->nullable();
            $table->string('language')->default('English');
            $table->string('thumbnail')->nullable();
            $table->string('pdf_file');
            $table->string('download_url')->nullable();
            $table->string('slug')->unique();
            $table->boolean('is_featured')->default(false);
            $table->integer('download_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebooks');
    }
};
