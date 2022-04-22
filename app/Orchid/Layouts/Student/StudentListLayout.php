<?php

namespace App\Orchid\Layouts\Student;

use App\Models\Student;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class StudentListLayout extends Table
{
    /**
     * @var string
     */
    protected $target = 'students';

    /**
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::set('name', 'Имя')
                ->render(function(Student $student){
                    return Link::make($student->name)
                        ->route('platform.student.edit', $student);
                }),
            TD::set('surname', 'Фамилия'),
            TD::set('nickname', 'Ник'),
            TD::set('group_id', 'Группа')
                ->render(function(Student $student){
                    return $student->group->number ?? '-';
                }),
            TD::set('get_mailing', 'Рассылка')
                ->render(function(Student $student){
                    return ($student->get_mailing) ? 'Включена' : 'Выключена';
                }),
            TD::set('roles', 'Роли')
                ->render(function(Student $student){
                     return implode(', ', $student->roles->pluck('title')->toArray());
                }),
        ];
    }
}
