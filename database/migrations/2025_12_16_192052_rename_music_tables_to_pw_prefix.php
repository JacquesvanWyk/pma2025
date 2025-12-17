<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Skip if already migrated (tables already renamed)
        if (Schema::hasTable('pw_albums') && Schema::hasTable('pw_songs')) {
            return;
        }

        if (Schema::hasTable('songs')) {
            Schema::table('songs', function (Blueprint $table) {
                $table->dropForeign(['album_id']);
            });
            Schema::rename('songs', 'pw_songs');
        }

        if (Schema::hasTable('albums')) {
            Schema::rename('albums', 'pw_albums');
        }

        Schema::table('pw_songs', function (Blueprint $table) {
            $table->foreign('album_id')
                ->references('id')
                ->on('pw_albums')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pw_songs', function (Blueprint $table) {
            $table->dropForeign(['album_id']);
        });

        Schema::rename('pw_albums', 'albums');
        Schema::rename('pw_songs', 'songs');

        Schema::table('songs', function (Blueprint $table) {
            $table->foreign('album_id')
                ->references('id')
                ->on('albums')
                ->cascadeOnDelete();
        });
    }
};
