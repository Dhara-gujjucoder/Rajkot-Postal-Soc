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
        Schema::create('bulk_master', function (Blueprint $table) {
            $table->id();
            $table->integer('year_id');
            $table->string('month');
            $table->integer('is_ms_applicable')->default(0);
            $table->double('ms_value')->default(0);
            $table->integer('created_by');
            $table->double('total');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulk_master');
    }
};
