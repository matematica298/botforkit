<?php

namespace App\Orchid\Screens\Chat;

use App\Models\Chat;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class ChatEditScreen extends Screen
{
    /**
     * @var string
     */
    public $name = 'Добавление чата';

    /**
     * @var string
     */
    public $description = 'База данных Сырка по чатам ВК';

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * @param Chat $chat
     * @return array
     */
    public function query(Chat $chat): array
    {
        $this->exists = $chat->exists;

        if ($this->exists) {
            $this->name = 'Редактировать информацию о чате';
        }

        return [
            'chat' => $chat
        ];
    }

    /**
     * @return array
     */
    public function commandBar(): array
    {
        return [
            Button::make('Добавить чат')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

            Button::make('Обновить')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->exists),

            Button::make('Удалить')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->exists),
        ];
    }

    /**
     * @return array
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('chat.id')
                    ->title('ID чата'),
            ])
        ];
    }

    /**
     * @param Chat $chat
     * @param Request $request
     * @return RedirectResponse
     */
    public function createOrUpdate(Chat $chat, Request $request)
    {
        $chat->fill($request->get('chat'))->save();

        Alert::info('Чат был успешно добавлен');

        return redirect()->route('platform.chat.list');
    }

    /**
     * @param Chat $chat
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function remove(Chat $chat, Request $request)
    {
        $chat->delete()
            ? Alert::info('Вы успешно удалили данные о чате')
            : Alert::warning('Возникла ошибка при удалении данных о чате');

        return redirect()->route('platform.chat.list');
    }
}
