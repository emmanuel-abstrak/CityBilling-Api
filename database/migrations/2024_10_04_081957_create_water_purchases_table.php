<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('water_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->foreignId('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled', 'failed', 'abandoned'])->default('pending');
            $table->decimal('requested_amount', 20);
            $table->decimal('token_amount', 20);
            $table->decimal('price', 20)->nullable();
            $table->decimal('vat', 20)->nullable();
            $table->json('tariffs')->nullable();
            $table->string('payment_method')->nullable();
            $table->decimal('volume', 20, 4)->nullable();
            $table->string('token')->nullable();
            $table->string('redirect_url')->nullable();
            $table->string('poll_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('water_purchases');
    }
};
