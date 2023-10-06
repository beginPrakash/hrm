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
        Schema::create('employee_loan', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->string('loan_amount');
            $table->string('total_paid');
            $table->string('loan_date');
            $table->string('installment');
            $table->string('install_pending');
            $table->string('amount_pending');
            $table->string('out_kwd');
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
        Schema::dropIfExists('employee_loan');
    }
};
