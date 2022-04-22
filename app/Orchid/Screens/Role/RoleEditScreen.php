<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Role;

use App\Orchid\Layouts\Role\RoleEditLayout;
use App\Orchid\Layouts\Role\RolePermissionLayout;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Platform\Models\Role;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class RoleEditScreen extends Screen
{
    /**
     * @var string
     */
    public $name = 'Управление ролями';

    /**
     * @var string
     */
    public $description = 'Права доступа';

    /**
     * @var string
     */
    public $permission = 'platform.systems.roles';

    /**
     * @var bool
     */
    private $exist = false;

    /**
     * @param Role $role
     * @return array
     */
    public function query(Role $role): array
    {
        $this->exist = $role->exists;

        return [
            'role'       => $role,
            'permission' => $role->getStatusPermission(),
        ];
    }

    /**
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [
            Button::make('Сохранить')
                ->icon('check')
                ->method('save'),

            Button::make('Удалить')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->exist),
        ];
    }

    /**
     * @return string[]
     */
    public function layout(): array
    {
        return [
            RoleEditLayout::class,
            RolePermissionLayout::class,
        ];
    }

    /**
     * @param Role $role
     * @param Request $request
     * @return RedirectResponse
     */
    public function save(Role $role, Request $request)
    {
        $request->validate([
            'role.slug' => 'required|unique:roles,slug,'.$role->id,
        ]);

        $role->fill($request->get('role'));

        $role->permissions = collect($request->get('permissions'))
            ->map(function ($value, $key) {
                return [base64_decode($key) => $value];
            })
            ->collapse()
            ->toArray();

        $role->save();

        Toast::info('Роль была сохранена');

        return redirect()->route('platform.systems.roles');
    }

    /**
     * @param Role $role
     * @return RedirectResponse
     * @throws Exception
     */
    public function remove(Role $role)
    {
        $role->delete();

        Toast::info('Роль была удалена');

        return redirect()->route('platform.systems.roles');
    }
}
