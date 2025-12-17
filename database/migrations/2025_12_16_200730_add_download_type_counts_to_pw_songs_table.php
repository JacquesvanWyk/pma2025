<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pw_songs', function (Blueprint $table) {
            if (! Schema::hasColumn('pw_songs', 'audio_download_count')) {
                $table->unsignedInteger('audio_download_count')->default(0)->after('download_count');
            }
            if (! Schema::hasColumn('pw_songs', 'video_download_count')) {
                $table->unsignedInteger('video_download_count')->default(0)->after('audio_download_count');
            }
            if (! Schema::hasColumn('pw_songs', 'lyrics_download_count')) {
                $table->unsignedInteger('lyrics_download_count')->default(0)->after('video_download_count');
            }
            if (! Schema::hasColumn('pw_songs', 'bundle_download_count')) {
                $table->unsignedInteger('bundle_download_count')->default(0)->after('lyrics_download_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pw_songs', function (Blueprint $table) {
            $table->dropColumn(['audio_download_count', 'video_download_count', 'lyrics_download_count', 'bundle_download_count']);
        });
    }
};
