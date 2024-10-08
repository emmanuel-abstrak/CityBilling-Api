<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tariff_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('suburb_id')->references('id')->on('suburbs')->onDelete('cascade');
            $table->decimal('min_size');
            $table->decimal('max_size');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tariff_groups');
    }
};
