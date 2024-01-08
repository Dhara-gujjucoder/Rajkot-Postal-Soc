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
        Schema::create('financial_year', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('start_year');
            $table->string('start_month');
            $table->string('end_year');
            $table->string('end_month');
            $table->boolean('status')->default(1);	
            $table->boolean('is_current')->default(0);	
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_year');
    }
};
