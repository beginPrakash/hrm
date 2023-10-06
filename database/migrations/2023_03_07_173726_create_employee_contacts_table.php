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
        Schema::create('employee_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->string('pri_con_name');
            $table->string('sec_con_name');
            $table->string('pri_con_relation');
            $table->string('sec_con_relation');
            $table->string('pri_con_phone');
            $table->string('pri_con_phone2');
            $table->string('sec_con_phone');
            $table->string('sec_con_phone2');
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
        Schema::dropIfExists('employee_contacts');
    }
};
