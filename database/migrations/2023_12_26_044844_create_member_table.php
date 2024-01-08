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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('company');
            $table->string('designation');
            $table->string('gender');
            $table->string('profile_picture')->nullable();
            $table->string('mobile_no');
            $table->string('whatsapp_no')->nullable();
            $table->string('aadhar_card_no')->nullable();
            $table->string('aadhar_card')->nullable();
            $table->string('pan_no')->nullable();
            $table->string('pan_card')->nullable();
            $table->longText('current_address');
            $table->longText('parmenant_address')->nullable();
            $table->bigInteger('salary');
            $table->bigInteger('da')->nullable();;
            $table->string('nominee_name')->nullable();;
            $table->string('nominee_relation')->nullable();;
            $table->string('registration_no');
            $table->string('department_id_proof');
            $table->string('saving_account_no')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->longText('branch_address')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('bank_name')->nullable();
            $table->boolean('status')->default(1);
            $table->string('signature')->nullable();;
            $table->string('witness_signature')->nullable();;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member');
    }
};
