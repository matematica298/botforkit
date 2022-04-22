<?php

namespace App\Orchid\Screens\Halloween\Character;

use App\Models\Group;
use App\Models\Halloween\Character;
use App\Orchid\Layouts\Halloween\CharacterListLayout;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class CharacterListScreen extends Screen
{
    /**
     * @var string
     */
    public $name = 'Группы персонажей';

    /**
     * @var string
     */
    public $description = 'Группы персонаж для эвента на Хэллоуин';

    /**
     * @var string[]
     */
    public $permission = [
        'event.manage'
    ];

    /**
     * @return array
     */
    public function query(): array
    {
        return [
            'characters' => Character::query()->orderBy('created_at', 'desc')->paginate(),
            'sides' => Character::query()->select('side', DB::raw('count(*) as total'))->groupBy('side')->pluck('total', 'side')->all(),
        ];
    }

    /**
     * @return array
     */
    public function commandBar(): array
    {
        return [
            Link::make('Добавить персонажа')
                ->icon('plus')
                ->route('platform.halloween.character.edit'),

            Button::make('Освободить всех персонажей')
                ->icon('circle_thin')
                ->method('getFreeCharacters')
                ->confirm('Вы уверены? Все привязки студентов к персонажам будут сброшены.'),
        ];
    }

    /**
     * @return array
     */
    public function layout(): array
    {
        return [
            Layout::view('orchid.halloween.character.list'),
            CharacterListLayout::class
        ];
    }

    /**
     * Функция освобождения всех персонажей
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getFreeCharacters()
    {
        Character::query()->where('busy', true)->update([
            'busy' => false,
            'student_id' => null,
        ]);

        return redirect()->route('platform.halloween.character.list');
    }
}
