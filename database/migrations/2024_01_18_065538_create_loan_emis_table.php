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
        Schema::create('loan_emis', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('loan_master_id');
            $table->string('month');
            $table->bigInteger('user_id');
            $table->double('principal_amt')->default(0);
            $table->double('interest')->default(0);
            $table->double('interest_amt')->default(0);
            $table->double('emi')->default(0);
            $table->double('emi_amt')->default(0);
            $table->double('amount')->nullable();
            $table->string('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_emis');
    }
};
