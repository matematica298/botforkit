<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('schedule_id'); # id расписания
            $table->unsignedInteger('group_id'); # id группы
            $table->enum('weekday', [0, 1, 2, 3, 4, 5, 6]); # день недели
            $table->enum('order', [1, 2, 3, 4, 5]); # номер пары
            $table->unsignedInteger('subject_id'); # id предмета
            $table->unsignedInteger('teacher_id')->nullable(); # id преподавателя
            $table->string('cab')->nullable(); # кабинет
            $table->enum('even', ["even", "odd", "nvm"])->nullable();
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
        Schema::dropIfExists('lessons');
    }
}
