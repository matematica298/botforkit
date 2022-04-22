<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchanges', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('group_id');
            $table->date('date');
            $table->enum('order', [1,2,3,4,5]);
            $table->string('title')->nullable(); # название предмета
            $table->string('old_title')->nullable(); # заменяемый предмет
            $table->string('cab')->nullable();
            $table->unsignedInteger('teacher_id')->nullable();
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
        Schema::dropIfExists('exchanges');
    }
}
