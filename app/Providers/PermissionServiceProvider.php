<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard)
    {
        $permissions = ItemPermission::group('Дополнительно')
            ->addPermission('vk.info', 'ВК')
            ->addPermission('schedule.manage', 'Расписание')
            ->addPermission('college.manage', 'Колледж')
            ->addPermission('system.manage', 'Пользователи и роли')
            ->addPermission('event.manage', 'Эвенты');

        $dashboard->registerPermissions($permissions);
    }
}
