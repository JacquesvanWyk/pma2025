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
        Schema::table('pw_albums', function (Blueprint $table) {
            $table->string('mp3_bundle_path')->nullable()->after('audio_bundle_path');
            $table->string('wav_bundle_path')->nullable()->after('mp3_bundle_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pw_albums', function (Blueprint $table) {
            $table->dropColumn(['mp3_bundle_path', 'wav_bundle_path']);
        });
    }
};
