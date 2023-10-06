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
        Schema::create('scheduling', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('department');
            $table->integer('employee');
            $table->date('shift_on');
            $table->integer('shift');
            $table->string('min_start_time');
            $table->string('start_time');
            $table->string('max_start_time');
            $table->string('min_end_time');
            $table->string('end_time');
            $table->string('max_end_time');
            $table->string('break_time');
            $table->integer('extra_hours');
            $table->integer('publish');
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
        Schema::dropIfExists('scheduling');
    }
};
