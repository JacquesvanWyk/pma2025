<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('accommodation_types', function (Blueprint $table) {
            $table->boolean('is_day_visitor')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('accommodation_types', function (Blueprint $table) {
            $table->dropColumn('is_day_visitor');
        });
    }
};
