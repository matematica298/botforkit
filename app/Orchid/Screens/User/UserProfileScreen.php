<?php

declare(strict_types=1);

namespace App\Orchid\Screens\User;

use App\Orchid\Layouts\User\UserEditLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Orchid\Platform\Models\User;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Password;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class UserProfileScreen extends Screen
{
    /**
     * @var string
     */
    public $name = 'Профиль';

    /**
     * @var string
     */
    public $description = 'Базовая информация';

    /**
     * @var User
     */
    protected $user;

    /**
     * Query data.
     *
     * @param Request $request
     *
     * @return array
     */
    public function query(Request $request): array
    {
        $this->user = $request->user();

        return [
            'user' => $this->user,
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [
            DropDown::make('Настройки')
                ->icon('open')
                ->list([
                    ModalToggle::make('Изменить пароль')
                        ->icon('lock-open')
                        ->method('changePassword')
                        ->modal('password'),
                ]),

            Button::make('Сохранить')
                ->icon('check')
                ->method('save'),
        ];
    }

    /**
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): array
    {
        return [
            UserEditLayout::class,

            Layout::modal('password', [
                Layout::rows([
                    Password::make('old_password')
                        ->placeholder('Введите текущий пароль')
                        ->required()
                        ->title('Старый пароль')
                        ->help('Это пароль, который сейчас установлен на вашем аккаунте.'),

                    Password::make('password')
                        ->placeholder('Введите новый пароль')
                        ->required()
                        ->title('Новый пароль'),

                    Password::make('password_confirmation')
                        ->placeholder('Введите новый пароль')
                        ->required()
                        ->title('Подтвердите новый пароль')
                        ->help('Хороший пароль должен содержать от 8 до 15 символов и содержать цифру и букву в нижнем регистре.'),
                ]),
            ])
                ->title('Изменить пароль')
                ->applyButton('Update password'),
        ];
    }

    /**
     * @param Request $request
     */
    public function save(Request $request)
    {
        $request->validate([
            'user.name'  => 'required|string',
            'user.email' => 'required|unique:users,email,'.$request->user()->id,
        ]);

        $request->user()
            ->fill($request->get('user'))
            ->save();

        Toast::info('Профиль обновлён');
    }

    /**
     * @param Request $request
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|password:web',
            'password'     => 'required|confirmed',
        ]);

        tap($request->user(), function ($user) use ($request) {
            $user->password = Hash::make($request->get('password'));
        })->save();

        Toast::info('Пароль изменён');
    }
}
