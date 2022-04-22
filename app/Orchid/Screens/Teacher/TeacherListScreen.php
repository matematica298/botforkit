<?php

namespace App\Orchid\Screens\Teacher;

use App\Models\Teacher;
use App\Orchid\Layouts\Teacher\TeacherListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class TeacherListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список преподавателей';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Преподаватели колледжа';

    /**
     * @return array
     */
    public function query(): array
    {
        return [
            'teachers' => Teacher::query()->filters()->defaultSort('name')->paginate()
        ];
    }

    /**
     * @return array
     */
    public function commandBar(): array
    {
        return [
            Link::make('Добавить преподавателя')
                ->icon('pencil')
                ->route('platform.teacher.edit'),
        ];
    }

    /**
     * @return string[]
     */
    public function layout(): array
    {
        return [
            TeacherListLayout::class
        ];
    }
}
