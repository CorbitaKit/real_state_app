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
        Schema::create('lot_groups', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('property_id');
            $table->string('color_label');
            $table->integer('sqr_meter');
            $table->integer('monthly_amortizations');
            $table->integer('amount_per_sqr_meter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lot_groups');
    }
};
