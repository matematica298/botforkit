<?php

namespace App\Orchid\Screens\Teacher;

use App\Models\Teacher;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class TeacherEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Добавить нового преподавателя';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Преподаватели колледжа';

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * @param Teacher $teacher
     * @return array
     */
    public function query(Teacher $teacher): array
    {
        $this->exists = $teacher->exists;

        if ($this->exists) {
            $this->name = 'Редактирование информации о преподавателе';
        }

        return [
            'teacher' => $teacher,
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
            Button::make('Добавить преподавателя')
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
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('teacher.name')
                    ->title('ФИО преподавателя'),

                Input::make('teacher.position')
                    ->title('Должность'),

                Input::make('teacher.email')
                    ->title('Электронная почта'),
            ])
        ];
    }

    /**
     * @param Teacher $teacher
     * @param Request $request
     * @return RedirectResponse
     */
    public function createOrUpdate(Teacher $teacher, Request $request)
    {
        $teacher->fill($request->get('teacher'))->save();

        Alert::info('Преподаватель успешно добавлен');

        return redirect()->route('platform.teacher.list');
    }

    /**
     * @param Teacher $teacher
     * @return RedirectResponse
     * @throws Exception
     */
    public function remove(Teacher $teacher) {
        $teacher->delete()
            ? Alert::info('Вы успешно удалили преподавателя')
            : Alert::warning('Возникла ошибка');

        return redirect()->route('platform.teacher.list');
    }
}
