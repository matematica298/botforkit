<?php

namespace App\Orchid\Screens\Subject;

use App\Models\Group;
use App\Models\Subject;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class SubjectEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Добавить новый предмет';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Предметы колледжа';

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * @param Subject $subject
     * @return Subject[]
     */
    public function query(Subject $subject): array
    {
        $this->exists = $subject->exists;

        if ($this->exists) {
            $this->name = 'Редактировать предмет';
        }

        return [
            'subject' => $subject
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
            Button::make('Добавить предмет')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

            Button::make('Обновить')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->exists),
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('subject.name')
                    ->title('Название предмета'),

                Input::make('subject.normal_name')
                    ->title('Нормальное название предмета'),
            ])
        ];
    }

    public function createOrUpdate(Subject $subject, Request $request)
    {
        $subject->fill($request->get('subject'))->save();

        Alert::info('Предмет был успешно добавлен');

        return redirect()->route('platform.subject.list');
    }
}
