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
        Schema::create('addschedule', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('department')->nullable();
            $table->bigInteger('employee')->nullable();
            $table->dateTime('date')->nullable();
            $table->bigInteger('shifts')->nullable();
            $table->time('min_start_time')->nullable();
            $table->time('start_time')->nullable();
            $table->time('max_start_time')->nullable();
            $table->time('min_end_time');
            $table->time('end_time');
            $table->time('max_end_time');
            $table->time('break_time');
            $table->time('accept_extra_hours')->nullable();
            $table->time('publish')->nullable();
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
        Schema::dropIfExists('addschedule');
    }
};
