<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_statements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->decimal('total', 25)->comment('In USD');
            $table->decimal('paid', 25)->default(0)->comment('In USD');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_statements');
    }
};
