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
            $table->foreignId('property_id')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('rates_total', 20, 2)->comment('In USD');
            $table->decimal('refuse_total', 20, 2)->comment('In USD');
            $table->decimal('sewer_total', 20, 2)->comment('In USD');
            $table->decimal('rates_paid', 20, 2)->default(0)->comment('In USD');
            $table->decimal('refuse_paid', 20, 2)->default(0)->comment('In USD');
            $table->decimal('sewer_paid', 20, 2)->default(0)->comment('In USD');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_statements');
    }
};
