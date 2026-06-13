<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('camp_tshirt_orders', function (Blueprint $table) {
            $table->string('delivery')->default('collect')->after('phone'); // collect|pudo
            $table->decimal('delivery_fee', 10, 2)->default(0)->after('delivery');
        });
    }

    public function down(): void
    {
        Schema::table('camp_tshirt_orders', function (Blueprint $table) {
            $table->dropColumn(['delivery', 'delivery_fee']);
        });
    }
};
