<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('music_style_presets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->text('style_description');
            $table->string('genre')->nullable();
            $table->string('mood')->nullable();
            $table->string('instruments')->nullable();
            $table->string('tempo')->nullable();
            $table->boolean('is_global')->default(false);
            $table->integer('usage_count')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'is_global']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('music_style_presets');
    }
};
