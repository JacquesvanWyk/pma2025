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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->enum('gateway', ['payfast', 'paystack', 'paypal']);
            $table->string('transaction_reference')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('ZAR');
            $table->string('donor_email')->nullable();
            $table->string('donor_name')->nullable();
            $table->enum('status', ['pending', 'successful', 'failed', 'cancelled'])->default('pending');
            $table->boolean('is_recurring')->default(false);
            $table->string('item_name')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
