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
        Schema::create('balance_sheet', function (Blueprint $table) {
            $table->id();
            $table->integer('ledger_ac_id');
            $table->integer('provision_fb');
            $table->integer('provision_member_welfare');
            $table->integer('provision_reserve_fund');
            $table->integer('provision_reserve_bonus');
            $table->integer('provision_reserve_charity');
            $table->integer('provision_dividend_equity_fund');
            $table->integer('provision_federation_fund');
            $table->integer('year_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_sheet');
    }
};
