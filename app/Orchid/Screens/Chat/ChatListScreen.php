<?php

namespace App\Orchid\Screens\Chat;

use App\Models\Chat;
use App\Orchid\Layouts\Chat\ChatListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class ChatListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Чаты';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'База данных Сырка по чатам ВК';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'chats' => Chat::query()->paginate()
        ];
    }

    /**
     * @return array
     */
    public function commandBar(): array
    {
        return [
            Link::make('Добавить чат')
                ->icon('pencil')
                ->route('platform.chat.edit'),
        ];
    }

    /**
     * @return string[]
     */
    public function layout(): array
    {
        return [
            ChatListLayout::class
        ];
    }
}
