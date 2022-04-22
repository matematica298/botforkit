<?php

namespace App\Orchid\Screens\StudentRole;

use App\Models\StudentRole;
use App\Orchid\Layouts\StudentRole\StudentRoleListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class StudentRoleListScreen extends Screen
{
    /**
     * @var string
     */
    public $name = 'Роли студентов';

    /**
     * @var string
     */
    public $description = 'Список возможных ролей у студентов';

    /**
     * @return array
     */
    public function query(): array
    {
        return [
            'student_roles' => StudentRole::query()->paginate()
        ];
    }

    /**
     * @return array
     */
    public function commandBar(): array
    {
        return [
            Link::make('Добавить роль')
                ->icon('pencil')
                ->route('platform.student_role.edit'),
        ];
    }

    /**
     * @return string[]
     */
    public function layout(): array
    {
        return [
            StudentRoleListLayout::class
        ];
    }
}
