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
        Schema::create('bulk_entry_master', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('year_id');
            $table->bigInteger('department_id');
            $table->bigInteger('rec_no')->nullable();
            $table->string('month')->nullable();
            $table->integer('is_ms_applicable')->default(0);
            $table->double('ms_value')->default(0);
            $table->double('month_total')->default(0);
            $table->double('department_total')->default(0);
            $table->integer('status')->default(1);
            $table->bigInteger('created_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulk_entry_master');
    }
};
