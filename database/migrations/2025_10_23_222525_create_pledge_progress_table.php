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
        Schema::create('pledge_progress', function (Blueprint $table) {
            $table->id();
            $table->decimal('current_amount', 10, 2)->default(0);
            $table->string('month');
            $table->decimal('goal_amount', 10, 2)->default(35000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pledge_progress');
    }
};
