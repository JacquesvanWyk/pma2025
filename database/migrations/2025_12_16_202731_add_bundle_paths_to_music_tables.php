<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pw_albums', function (Blueprint $table) {
            $table->string('audio_bundle_path')->nullable()->after('full_download_count');
            $table->string('video_bundle_path')->nullable()->after('audio_bundle_path');
            $table->string('full_bundle_path')->nullable()->after('video_bundle_path');
            $table->timestamp('bundles_generated_at')->nullable()->after('full_bundle_path');
        });

        Schema::table('pw_songs', function (Blueprint $table) {
            $table->string('bundle_path')->nullable()->after('bundle_download_count');
            $table->timestamp('bundle_generated_at')->nullable()->after('bundle_path');
        });
    }

    public function down(): void
    {
        Schema::table('pw_albums', function (Blueprint $table) {
            $table->dropColumn(['audio_bundle_path', 'video_bundle_path', 'full_bundle_path', 'bundles_generated_at']);
        });

        Schema::table('pw_songs', function (Blueprint $table) {
            $table->dropColumn(['bundle_path', 'bundle_generated_at']);
        });
    }
};
