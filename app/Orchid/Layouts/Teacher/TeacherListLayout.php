<?php

namespace App\Orchid\Layouts\Teacher;

use App\Models\Teacher;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class TeacherListLayout extends Table
{
    /**
     * @var string
     */
    protected $target = 'teachers';

    /**
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::set('name', 'ФИО')
                ->render(function(Teacher $teacher){
                    return Link::make($teacher->name)
                        ->route('platform.teacher.edit', $teacher);
                }),
            TD::set('position', 'Должность'),
            TD::set('email', 'Электронная почта'),
            TD::set('created_at', 'Добавлен'),
            TD::set('updated_at', 'Последнее изменение')->defaultHidden(),
        ];
    }
}
