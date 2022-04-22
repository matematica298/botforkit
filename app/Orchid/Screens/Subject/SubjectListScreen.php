<?php

namespace App\Orchid\Screens\Subject;

use App\Models\Subject;
use App\Orchid\Layouts\Subject\SubjectListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class SubjectListScreen extends Screen
{
    /**
     * @var string
     */
    public $name = 'Список предметов колледжа';

    /**
     * @var string
     */
    public $description = 'Предметы колледжа';

    /**
     * @return array
     */
    public function query(): array
    {
        return [
            'subjects' => Subject::query()->paginate()
        ];
    }

    /**
     * @return array
     */
    public function commandBar(): array
    {
        return [
            Link::make('Добавить предмет')
                ->icon('pencil')
                ->route('platform.subject.edit'),
        ];
    }

    /**
     * @return string[]
     */
    public function layout(): array
    {
        return [
            SubjectListLayout::class
        ];
    }
}
