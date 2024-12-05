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
        Schema::create('work_information', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('company_name')->nullable();
            $table->string('company_address')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_number')->nullable();
            $table->integer('length_of_stay')->nullable();
            $table->string('status')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->float('gross_monthly_income')->nullable();
            $table->string('job_title')->nullable();
            $table->string('position')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_information');
    }
};
