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
            $table->string('company_name');
            $table->string('company_address');
            $table->string('company_email');
            $table->string('company_number');
            $table->integer('length_of_stay');
            $table->string('status');
            $table->foreignId('user_id');
            $table->float('gross_monthly_income');
            $table->string('job_title');
            $table->string('position');
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
