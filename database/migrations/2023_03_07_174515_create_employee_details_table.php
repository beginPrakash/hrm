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
        Schema::create('employee_details', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->string('address');
            $table->string('religion');
            $table->string('state');
            $table->string('country');
            $table->string('pin_code');
            $table->string('c_id');
            $table->string('expi_c_id');
            $table->string('b_id');
            $table->string('expi_b_id');
            $table->string('marital_status');
            $table->string('child');
            $table->string('birthday');
            $table->string('gender');
            $table->string('spouse');
            $table->string('license');
            $table->string('license_exp');
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
        Schema::dropIfExists('employee_details');
    }
};
