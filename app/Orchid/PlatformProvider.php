<?php

namespace App\Orchid;

use Laravel\Scout\Searchable;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemMenu;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * @return ItemMenu[]
     */
    public function registerMainMenu(): array
    {
        return [
            ItemMenu::label('Группы колледжа')
                ->icon('organization')
                ->route('platform.group.list')
                ->permission('college.manage')
                ->title('Колледж'),
            ItemMenu::label('Преподаватели')
                ->icon('eyeglasses')
                ->permission('college.manage')
                ->route('platform.teacher.list'),

            ItemMenu::label('Расписание')
                ->title('Расписание')
                ->icon('note')
                ->permission('schedule.manage')
                ->route('platform.schedule.list'),
            ItemMenu::label('Предметы')
                ->icon('docs')
                ->permission('schedule.manage')
                ->route('platform.subject.list'),
            ItemMenu::label('Пары')
                ->icon('briefcase')
                ->permission('schedule.manage')
                ->route('platform.lesson.list'),
            ItemMenu::label('Замены')
                ->icon('list')
                ->permission('schedule.manage')
                ->route('platform.exchange.list'),

            ItemMenu::label('Студенты')
                ->title('Вконтакте')
                ->icon('user')
                ->permission('vk.info')
                ->route('platform.student.list'),
            ItemMenu::label('Чаты')
                ->icon('people')
                ->permission('vk.info')
                ->route('platform.chat.list'),
            ItemMenu::label('Роли')
                ->icon('layers')
                ->permission('vk.info')
                ->route('platform.student_role.list'),

            ItemMenu::label('Персонажи')
                ->title('Хэллоуин')
                ->icon('ghost')
                ->permission('event.manage')
                ->route('platform.halloween.character.list'),

            ItemMenu::label('Пользователи')
                ->title('Администрирование')
                ->icon('people')
                ->permission('system.manage')
                ->route('platform.systems.users'),
            ItemMenu::label('Роли пользователей')
                ->icon('social-dribbble')
                ->permission('system.manage')
                ->route('platform.systems.roles'),
        ];
    }

    /**
     * @return ItemMenu[]
     */
    public function registerProfileMenu(): array
    {
        return [
            ItemMenu::label('Профиль')
                ->route('platform.profile')
                ->icon('user'),
        ];
    }

    /**
     * @return ItemMenu[]
     */
    public function registerSystemMenu(): array
    {
        return [
            ItemMenu::label(__('Права доступа'))
                ->icon('lock')
                ->slug('Auth')
                ->active('platform.systems.*')
                ->permission('platform.systems.index')
                ->sort(1000),

            ItemMenu::label(__('Пользователи'))
                ->place('Auth')
                ->icon('user')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->sort(1000)
                ->title('Все зарегистрированные пользователи.'),

            ItemMenu::label(__('Роли пользователей'))
                ->place('Auth')
                ->icon('lock')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->sort(1000)
                ->title('Роли определяют к чему пользователь имеет доступ и какие действия он способен совершать.'),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function registerPermissions(): array
    {
        return [
            ItemPermission::group('Система')
                ->addPermission('platform.systems.roles', 'Роли пользователей')
                ->addPermission('platform.systems.users', 'Пользователи'),
        ];
    }

    /**
     * @return Searchable|string[]
     */
    public function registerSearchModels(): array
    {
        return [
            // ...Models
            // \App\Models\User::class
        ];
    }
}
