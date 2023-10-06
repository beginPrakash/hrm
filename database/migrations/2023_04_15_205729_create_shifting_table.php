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
        Schema::create('shifting', function (Blueprint $table) {
            $table->id();
            $table->time('min_start_time')->nullable();
            $table->time('start_time')->nullable();
            $table->time('max_start_time')->nullable();
            $table->time('min_end_time')->nullable();
            $table->time('end_time')->nullable();
            $table->time('max_end_time')->nullable();
            $table->time('break_time')->nullable();
            $table->string('recurring_shift')->nullable();
            $table->integer('repeat_every')->nullable();
            $table->string('week_day')->nullable();
            $table->time('end_on')->nullable();
            $table->string('indefinite')->nullable();
            $table->time('tag')->nullable();
            $table->time('note')->nullable();
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
        Schema::dropIfExists('shifting');
    }
};
