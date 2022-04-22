<?php

namespace App\Orchid\Screens\Schedule;

use App\Models\Schedule;
use App\Orchid\Layouts\Schedule\ScheduleListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class ScheduleListScreen extends Screen
{
    /**
     * @var string
     */
    public $name = 'Список расписаний';

    /**
     * @var string
     */
    public $description = 'Расписания колледжа';

    /**
     * @return array
     */
    public function query(): array
    {
        return [
            'schedules' => Schedule::query()->paginate()
        ];
    }

    /**
     * @return array
     */
    public function commandBar(): array
    {
        return [
            Link::make('Загрузить')
                ->icon('share-alt')
                ->route('platform.schedule.download'),
        ];
    }

    /**
     * @return string[]
     */
    public function layout(): array
    {
        return [
            ScheduleListLayout::class
        ];
    }
}
