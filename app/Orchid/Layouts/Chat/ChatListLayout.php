<?php

namespace App\Orchid\Layouts\Chat;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ChatListLayout extends Table
{
    /**
     * @var string
     */
    protected $target = 'chats';

    /**
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::set('id', 'ID')
        ];
    }
}
