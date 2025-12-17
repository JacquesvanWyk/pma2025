<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pw_albums', function (Blueprint $table) {
            if (! Schema::hasColumn('pw_albums', 'audio_download_count')) {
                $table->unsignedInteger('audio_download_count')->default(0)->after('download_count');
            }
            if (! Schema::hasColumn('pw_albums', 'video_download_count')) {
                $table->unsignedInteger('video_download_count')->default(0)->after('audio_download_count');
            }
            if (! Schema::hasColumn('pw_albums', 'full_download_count')) {
                $table->unsignedInteger('full_download_count')->default(0)->after('video_download_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pw_albums', function (Blueprint $table) {
            $table->dropColumn(['audio_download_count', 'video_download_count', 'full_download_count']);
        });
    }
};
