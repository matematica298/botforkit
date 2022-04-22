<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class UserEditLayout extends Rows
{
    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            Input::make('user.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title('Имя')
                ->placeholder('Имя пользователя'),

            Input::make('user.email')
                ->type('email')
                ->required()
                ->title('Электронная почта')
                ->placeholder('superemail@kitdev.ru'),
        ];
    }
}
