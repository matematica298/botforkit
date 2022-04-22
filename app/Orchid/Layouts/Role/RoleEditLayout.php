<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Role;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class RoleEditLayout extends Rows
{
    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            Input::make('role.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title('Название роли')
                ->placeholder('Название роли')
                ->help('Отображаемое название роли'),

            Input::make('role.slug')
                ->type('text')
                ->max(255)
                ->required()
                ->title('Системное название')
                ->placeholder('slug')
                ->help('Название роли внутри системы'),
        ];
    }
}
