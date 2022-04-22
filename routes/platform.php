<?php

declare(strict_types=1);

use App\Orchid\Screens\Group\GroupEditScreen;
use App\Orchid\Screens\Group\GroupListScreen;
use App\Orchid\Screens\Halloween\Character\CharacterListScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Profile'), route('platform.profile'));
    });

// Platform > System > Users
Route::screen('users/{users}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(function (Trail $trail, $user) {
        return $trail
            ->parent('platform.systems.users')
            ->push(__('Edit'), route('platform.systems.users.edit', $user));
    });

// Platform > System > Users > User
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.index')
            ->push(__('Users'), route('platform.systems.users'));
    });

// Platform > System > Roles > Role
Route::screen('roles/{roles}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(function (Trail $trail, $role) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Role'), route('platform.systems.roles.edit', $role));
    });

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Create'), route('platform.systems.roles.create'));
    });

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.index')
            ->push(__('Roles'), route('platform.systems.roles'));
    });

# Маршруты, связанные с группами колледжа
Route::screen('group/{group?}', GroupEditScreen::class)
    ->name('platform.group.edit');
Route::screen('groups', GroupListScreen::class)
    ->name('platform.group.list');

# Маршруты, связанные с преподавателями колледжа
Route::screen('teacher/{teacher?}', \App\Orchid\Screens\Teacher\TeacherEditScreen::class)
    ->name('platform.teacher.edit');
Route::screen('teachers', \App\Orchid\Screens\Teacher\TeacherListScreen::class)
    ->name('platform.teacher.list');

# Маршруты, связанные с предметами колледжа
Route::screen('subject/{subject?}', \App\Orchid\Screens\Subject\SubjectEditScreen::class)
    ->name('platform.subject.edit');
Route::screen('subjects', \App\Orchid\Screens\Subject\SubjectListScreen::class)
    ->name('platform.subject.list');

# Маршруты, связанные с расписанием
Route::screen('schedule/download', \App\Orchid\Screens\Schedule\ScheduleDownloadScreen::class)
    ->name('platform.schedule.download');
Route::screen('schedules', \App\Orchid\Screens\Schedule\ScheduleListScreen::class)
    ->name('platform.schedule.list');

# Маршруты, связанные с парами
Route::screen('lesson/{lesson?}', \App\Orchid\Screens\Lesson\LessonEditScreen::class)
    ->name('platform.lesson.edit');
Route::screen('lessons', \App\Orchid\Screens\Lesson\LessonListScreen::class)
    ->name('platform.lesson.list');

# Маршруты, связанные с заменами
Route::screen('exchange/download', \App\Orchid\Screens\Exchange\ExchangeDownloadScreen::class)
    ->name('platform.exchange.download');
Route::screen('exchange/{date}', \App\Orchid\Screens\Exchange\ExchangeViewScreen::class)
    ->name('platform.exchange.view');
Route::screen('exchanges', \App\Orchid\Screens\Exchange\ExchangeListScreen::class)
    ->name('platform.exchange.list');
Route::screen('exchange/{exchange}/edit', \App\Orchid\Screens\Exchange\ExchangeEditScreen::class)
    ->name('platform.exchange.edit');

# Маршруты, связанные со студентами
Route::screen('student/{student?}', \App\Orchid\Screens\Student\StudentEditScreen::class)
    ->name('platform.student.edit');
Route::screen('students', \App\Orchid\Screens\Student\StudentListScreen::class)
    ->name('platform.student.list');

# Маршруты, связанные с чатами
Route::screen('chat/{chat?}', \App\Orchid\Screens\Chat\ChatEditScreen::class)
    ->name('platform.chat.edit');
Route::screen('chats', \App\Orchid\Screens\Chat\ChatListScreen::class)
    ->name('platform.chat.list');

# Маршруты, связанные с ролями студентов
Route::screen('student_role/{student_role?}', \App\Orchid\Screens\StudentRole\StudentRoleEditScreen::class)
    ->name('platform.student_role.edit');
Route::screen('student_roles', \App\Orchid\Screens\StudentRole\StudentRoleListScreen::class)
    ->name('platform.student_role.list');

# Маршруты, связанные с эвентом "Хэллоуин"
Route::screen('halloween/characters', CharacterListScreen::class)
    ->name('platform.halloween.character.list');
Route::screen('halloween/{character?}', \App\Orchid\Screens\Halloween\Character\CharacterEditScreen::class)
    ->name('platform.halloween.character.edit');
