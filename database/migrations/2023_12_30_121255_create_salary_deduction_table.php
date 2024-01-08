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
        Schema::create('salary_deduction', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('uid');
            $table->string('year');
            $table->string('month');
            $table->bigInteger('rec_no')->nullable();
            $table->double('principal')->nullable();
            $table->double('interest')->nullable();
            $table->double('fixed')->nullable();
            $table->double('total_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_deduction');
    }
};
