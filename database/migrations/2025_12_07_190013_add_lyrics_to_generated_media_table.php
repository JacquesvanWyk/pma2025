<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('generated_media', function (Blueprint $table) {
            $table->text('lyrics')->nullable()->after('prompt');
            $table->string('title')->nullable()->after('prompt');
            $table->foreignId('style_preset_id')->nullable()->after('settings')
                ->constrained('music_style_presets')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('generated_media', function (Blueprint $table) {
            $table->dropForeign(['style_preset_id']);
            $table->dropColumn(['lyrics', 'title', 'style_preset_id']);
        });
    }
};
