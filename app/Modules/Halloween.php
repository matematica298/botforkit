<?php

namespace App\Modules;

use App\Models\Halloween\Character;

class Halloween extends Auxiliary
{
    /**
     * Приветственное сообщение главного бота
     * @param Character $character
     * @return string
     */
    public static function welcomeMessage(Character $character)
    {
        return "Поприветствуем в нашем чате {$character->name }!\n{$character->description}\n\nСторона: {$character->normal_side}";
    }
}
