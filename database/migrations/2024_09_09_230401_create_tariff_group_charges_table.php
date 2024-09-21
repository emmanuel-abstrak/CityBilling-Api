<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tariff_group_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tariff_group_id')->references('id')->on('tariff_groups')->onDelete('cascade');
            $table->foreignId('property_type_id')->references('id')->on('property_types')->onDelete('cascade');
            $table->foreignId('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->decimal('price', 25);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tariff_group_charges');
    }
};
