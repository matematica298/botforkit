<?php

namespace App\Orchid\Layouts\Halloween;

use App\Models\Halloween\Character;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CharacterListLayout extends Table
{
    /**
     * @var string
     */
    protected $target = 'characters';

    /**
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::set('id', 'ID')
                ->align(TD::ALIGN_CENTER),
            TD::set('image', 'Изображение')
                ->render(function(Character $character) {
                    return "<img src='{$character->image}' width='100' alt='{$character->name}'>";
                })
                ->align(TD::ALIGN_CENTER),
            TD::set('name', 'Имя персонажа')
                ->align(TD::ALIGN_CENTER)
                ->render(function(Character $character) {
                    return Link::make($character->name)
                        ->route('platform.halloween.character.edit', $character);
                }),
            TD::set('side', 'Тип')
                ->align(TD::ALIGN_CENTER)
                ->render(function(Character $character) {
                    return $character->normal_side;
                }),
            TD::set('busy', 'Занят')
                ->align(TD::ALIGN_CENTER)
                ->render(function(Character $character) {
                    return ($character->busy) ? 'Занят' : '';
                }),
            TD::set('created_at', 'Добавлен')
                ->align(TD::ALIGN_CENTER),
        ];
    }
}
