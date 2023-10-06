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
        Schema::create('attendance_details', function (Blueprint $table) {
            $table->id();
            $table->integer('attendance_id');
            $table->integer('user_id');
            $table->integer('employee_id');
            $table->integer('department');
            $table->date('attendance_on');
            $table->string('attendance_time');
            $table->enum('punch_state', ['clockin', 'clockout']);
            $table->string('work_code');
            $table->integer('data_source');
            $table->enum('status', ['active', 'inactive']);
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
        Schema::dropIfExists('attendance_details');
    }
};
