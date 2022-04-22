<?php

namespace App\Orchid\Layouts\Schedule;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ScheduleListLayout extends Table
{
    /**
     * @var string
     */
    protected $target = 'schedules';

    /**
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::set('id', 'ID расписания'),
            TD::set('start', 'С какого действует'),
        ];
    }
}
