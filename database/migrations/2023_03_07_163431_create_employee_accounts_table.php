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
        Schema::create('employee_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->string('bank_name');
            $table->string('branch_code');
            $table->string('account_number');
            $table->string('ifc_number');
            $table->string('swift_code');
            $table->string('branch_name');
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
        Schema::dropIfExists('employee_accounts');
    }
};
