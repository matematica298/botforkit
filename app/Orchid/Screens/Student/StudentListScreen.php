<?php

namespace App\Orchid\Screens\Student;

use App\Models\Halloween\Character;
use App\Models\Student;
use App\Orchid\Layouts\Student\StudentListLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class StudentListScreen extends Screen
{
    /**
     * @var string
     */
    public $name = 'Список студентов';

    /**
     * @var string
     */
    public $description = 'База данных студентов VK';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'students' => Student::query()->orderBy('group_id')->paginate(50)
        ];
    }

    /**
     * @return array
     */
    public function commandBar(): array
    {
        return [
            Link::make('Добавить')
                ->icon('share-alt')
                ->route('platform.student.edit'),
        ];
    }

    /**
     * @return string[]
     */
    public function layout(): array
    {
        return [
            StudentListLayout::class
        ];
    }
}
