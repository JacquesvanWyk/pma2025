<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('camp_tshirt_orders', function (Blueprint $table) {
            $table->json('lines')->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('camp_tshirt_orders', function (Blueprint $table) {
            $table->dropColumn('lines');
        });
    }
};
