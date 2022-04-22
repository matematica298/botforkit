<?php

namespace App\Orchid\Screens\Group;

use App\Models\Group;
use App\Models\Teacher;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class GroupEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Добавление новой группы';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Группы колледжа';

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * @param Group $group
     * @return array
     */
    public function query(Group $group): array
    {
        $this->exists = $group->exists;

        if ($this->exists) {
            $this->name = 'Редактирование группы';
        }

        return [
            'group' => $group,
        ];
    }

    /**
     * @return array
     */
    public function commandBar(): array
    {
        return [
            Button::make('Добавить группу')
                ->icon('plus')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

            Button::make('Обновить группу')
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
                Input::make('group.number')
                    ->title('Номер группы')
                    ->placeholder('Номер группы'),

                Select::make('group.course')
                    ->options([
                        '1' => 'Первый курс',
                        '2' => 'Второй курс',
                        '3' => 'Третий курс',
                        '4' => 'Четвёртый курс',
                    ])
                    ->title('Курс группы'),

                Relation::make('group.curator_id')
                    ->fromModel(Teacher::class, 'name')
                    ->title('Куратор группы'),
            ])
        ];
    }

    /**
     * @param Group $group
     * @param Request $request
     * @return RedirectResponse
     */
    public function createOrUpdate(Group $group, Request $request)
    {
        $group->fill($request->get('group'))->save();

        Alert::info('Группа была успешно создана');

        return redirect()->route('platform.group.list');
    }

    /**
     * @param Group $group
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function remove(Group $group, Request $request)
    {
        $group->delete()
            ? Alert::info('Группа успешно удалена')
            : Alert::warning('Возникла ошибка при удалении группы');

        return redirect()->route('platform.group.list');
    }
}
