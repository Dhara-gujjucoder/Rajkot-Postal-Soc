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
        Schema::create('bulk_entries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bulk_master_id');
            $table->bigInteger('user_id');
            $table->bigInteger('member_id');
            $table->bigInteger('year_id');
            $table->bigInteger('ledger_group_id');
            $table->string('month');
            $table->bigInteger('rec_no')->nullable();
            $table->double('principal')->nullable();
            $table->double('interest')->nullable();
            $table->double('fixed')->nullable();
            $table->double('ms')->nullable();
            $table->double('total_amount');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulk_entry');
    }
};
