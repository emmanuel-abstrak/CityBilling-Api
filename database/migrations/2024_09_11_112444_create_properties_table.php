<?php

use App\Library\Enums\MeterProvider;
use App\Library\Enums\PropertyType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{


    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->foreignId('suburb_id')->nullable()->references('id')->on('suburbs')->nullOnDelete();
            $table->foreignId('tariff_group_id')->nullable()->references('id')->on('tariff_groups')->nullOnDelete();
            $table->decimal('size');
            $table->string('meter')->index()->unique();
            $table->enum('meter_provider', MeterProvider::values())->index();
            $table->enum('type', PropertyType::values())->index();
            $table->string('address');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
