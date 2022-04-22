<?php

namespace App\Orchid\Screens\Halloween\Character;

use App\Models\Halloween\Character;
use App\Models\Student;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class CharacterEditScreen extends Screen
{
    /**
     * @var string
     */
    public $name = 'Добавление персонажа';

    /**
     * @var string
     */
    public $description = 'Персонаж для эвента на Хэллоуин';

    /**
     * @var string[]
     */
    public $permission = [
        'event.manage'
    ];

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * @param Character $character
     * @return array
     */
    public function query(Character $character): array
    {
        $this->exists = $character->exists;

        if ($this->exists) {
            $this->name = 'Редактирование персонажа';
        }

        return [
            'character' => $character
        ];
    }

    /**
     * @return array
     */
    public function commandBar(): array
    {
        return [
            Button::make('Добавить')
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
                Input::make('character.name')
                    ->title('Имя персонажа'),

                Select::make('character.side')
                    ->options([
                        'evil' => 'Отрицательный',
                        'good' => 'Положительный',
                        'neutral' => 'Нейтральный',
                    ])
                    ->value('neutral')
                    ->title('Тип персонажа'),

                Cropper::make('character.image')
                    ->title('Изображение для персонажа')
                    ->targetRelativeUrl()
                    ->width(500)
                    ->height(500),

                TextArea::make('character.description')
                    ->rows(10)
                    ->placeholder('Этот персонаж был одним из тех...')
                    ->title('Описание персонажа'),

                Relation::make('character.student_id')
                    ->fromModel(Student::class, 'name')
                    ->title('Студент'),
            ]),
        ];
    }

    /**
     * @param Character $character
     * @param Request $request
     * @return RedirectResponse
     */
    public function createOrUpdate(Character $character, Request $request)
    {
        $data = $request->get('character');

        if (isset($data['student_id'])) {
            $data['busy'] = true;
        }

        $character->fill($data)->save();

        Alert::info('Персонаж был успешно добавлен');

        return redirect()->route('platform.halloween.character.list');
    }

    /**
     * @param Character $character
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function remove(Character $character, Request $request)
    {
        $character->delete()
            ? Alert::info('Персонаж успешно удалён')
            : Alert::warning('Возникла ошибка при удалении персонажа');

        return redirect()->route('platform.halloween.character.list');
    }
}
