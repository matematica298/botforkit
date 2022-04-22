<?php

namespace App\Orchid\Layouts\StudentRole;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class StudentRoleListLayout extends Table
{
    /**
     * @var string
     */
    protected $target = 'student_roles';

    /**
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::set('title', 'Название роли'),
            TD::set('short_title', 'Короткое название роли'),
        ];
    }
}
