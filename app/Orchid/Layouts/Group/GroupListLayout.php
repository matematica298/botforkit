<?php

namespace App\Orchid\Layouts\Group;

use App\Models\Group;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class GroupListLayout extends Table
{
    /**
     * @var string
     */
    protected $target = 'groups';

    /**
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::set('number', 'Номер')
                ->render(function(Group $group){
                    return Link::make($group->number)
                        ->route('platform.group.edit', $group);
                })
                ->sort(),
            TD::set('course', 'Курс')
                ->sort(),
            TD::set('curator_id', 'Куратор')
                ->render(function(Group $group){
                    return $group->curator->shortname ?? 'Не указан';
                }),
            TD::set('updated_at', 'Последнее изменение'),
        ];
    }
}
