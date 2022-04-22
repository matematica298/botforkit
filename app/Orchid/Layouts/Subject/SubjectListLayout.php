<?php

namespace App\Orchid\Layouts\Subject;

use App\Models\Subject;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SubjectListLayout extends Table
{
    /**
     * @var string
     */
    protected $target = 'subjects';

    /**
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::set('name', 'Название (по расписанию)')
                ->render(function(Subject $subject){
                    return Link::make($subject->name)
                        ->route('platform.subject.edit', $subject);
                }),

            TD::set('normal_name', 'Отображаемое название'),
        ];
    }
}
