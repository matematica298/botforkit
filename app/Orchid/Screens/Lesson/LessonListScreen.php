<?php

namespace App\Orchid\Screens\Lesson;

use App\Models\Lesson;
use App\Orchid\Layouts\Lesson\LessonListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class LessonListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Просмотр пары';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Пары расписания';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'lessons' => Lesson::paginate()
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [
            Link::make('Добавить пару')
                ->icon('pencil')
                ->route('platform.lesson.edit'),
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            LessonListLayout::class
        ];
    }
}
