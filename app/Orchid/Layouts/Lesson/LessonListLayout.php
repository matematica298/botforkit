<?php

namespace App\Orchid\Layouts\Lesson;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class LessonListLayout extends Table
{
    /**
     * @var string
     */
    protected $target = 'lessons';

    /**
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::set('schedule_id', 'ID расписания'),
            TD::set('group_id', 'ID группы')
                ->render(function($lesson){
                    return $lesson->group->number;
                }),
            TD::set('weekday', 'День недели'),
            TD::set('order', 'Номер пары'),
            TD::set('subject_id', 'Предмет')
                ->render(function($lesson){
                    return $lesson->subject->normal_name;
                }),
            TD::set('teacher_id', 'Преподаватель')
                ->render(function($lesson){
                    return $lesson->teacher->name ?? 'Не указан';
                }),
        ];
    }
}
