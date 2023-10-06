<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id(); 
            $table->string('first_name');
            $table->string('last_name');
            $table->string('civel_id');
            $table->string('email');
            $table->string('password');
            $table->string('conf_password');
            $table->string('emp_generated_id');
            $table->string('joining_date');
            $table->string('passport_no');
            $table->string('pass_expiry');
            $table->string('local_address');
            $table->string('phone');
            $table->string('visa_no');
            $table->string('company');
            $table->string('department');
            $table->string('designation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
