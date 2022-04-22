<?php

namespace App\Orchid\Screens\StudentRole;

use App\Models\StudentRole;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class StudentRoleEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'StudentRoleEditScreen';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'StudentRoleEditScreen';

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * @param StudentRole $student_role
     * @return StudentRole[]
     */
    public function query(StudentRole $student_role): array
    {
        $this->exists = $student_role->exists;

        if ($this->exists) {
            $this->name = 'Редактирование роли студента';
        }

        return [
            'student_role' => $student_role
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
            Button::make('Создать группу')
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
                Input::make('student_role.title')
                    ->title('Название роли'),
                Input::make('student_role.short_title')
                    ->title('Короткое название роли'),
            ])
        ];
    }

    public function createOrUpdate(StudentRole $student_role, Request $request)
    {
        $student_role->fill($request->get('student_role'))->save();

        Alert::info('Группа была успешно создана');

        return redirect()->route('platform.student_role.list');
    }

    /**
     * @param StudentRole $student_role
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function remove(StudentRole $student_role, Request $request)
    {
        $student_role->delete()
            ? Alert::info('Вы успешно удалили группу')
            : Alert::warning('Возникла ошибка при удалении группы');

        return redirect()->route('platform.student_role.list');
    }
}
