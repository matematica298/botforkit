<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Role;

use Orchid\Platform\Models\Role;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class RoleListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'roles';

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            TD::set('name', 'Название роли')
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(function (Role $role) {
                    return Link::make($role->name)
                        ->route('platform.systems.roles.edit', $role->id);
                }),

            TD::set('slug', 'Название роли в системе')
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT),

            TD::set('created_at', 'Создан')
                ->sort()
                ->render(function (Role $role) {
                    return $role->created_at->toDateTimeString();
                }),
        ];
    }
}
