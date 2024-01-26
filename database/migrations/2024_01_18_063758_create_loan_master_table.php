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
        Schema::create('loan_master', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('year_id');
            $table->string('month');
            $table->bigInteger('user_id');
            $table->date('start_month');
            $table->date('end_month');
            $table->bigInteger('loan_id')->nullable();
            $table->double('amount')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_master');
    }
};
