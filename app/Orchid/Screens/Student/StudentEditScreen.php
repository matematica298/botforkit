<?php

namespace App\Orchid\Screens\Student;

use App\Models\Group;
use App\Models\RoleStudent;
use App\Models\Student;
use App\Models\StudentRole;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class StudentEditScreen extends Screen
{
    /**
     * @var string
     */
    public $name = 'Добавление студента';

    /**
     * @var string
     */
    public $description = 'База данных Сырка по студентам';

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * @param Student $student
     * @return Student[]
     */
    public function query(Student $student): array
    {
        $this->exists = $student->exists;

        if ($this->exists) {
            $this->name = 'Редактирование информации о студенте';
        }

        return [
            'student' => $student
        ];
    }

    /**
     * @return array
     */
    public function commandBar(): array
    {
        return [
            Button::make('Добавить студента')
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
                Input::make('student.id')
                    ->title('VK ID'),
                Input::make('student.name')
                    ->title('Имя студента'),
                Input::make('student.surname')
                    ->title('Фамилия студента'),
                Relation::make('student.group_id')
                    ->fromModel(Group::class, 'number')
                    ->title('Группа студента'),
                Input::make('student.nickname')
                    ->title('Ник студента'),
                TextArea::make('student.description')
                    ->title('Описание студента'),
                Input::make('student.reputation')
                    ->type('number')
                    ->title('Репутация студента')
                    ->value(0),
                CheckBox::make('student.get_mailing')
                    ->title('Рассылка изменений')
                    ->sendTrueOrFalse()
                    ->placeholder('Включена/Отключена'),
                Relation::make('student.roles.')
                    ->fromModel(StudentRole::class, 'title')
                    ->multiple()
                    ->title('Роли'),
            ])
        ];
    }

    public function createOrUpdate(Student $student, Request $request)
    {
        $student->fill($request->get('student'))->save();

        $studentRoles = $student->roles->pluck('id')->toArray();

        foreach ($roles = ($request->get('student')['roles'] ?? []) as $role) {
            if (!in_array($role, $studentRoles)) {
                RoleStudent::query()->create([
                    'student_id' => $student->id,
                    'role_id' => $role,
                ]);
            }
        }

        foreach ($studentRoles as $studentRole) {
            if (!in_array($studentRole, $roles)) {
                RoleStudent::query()
                    ->where('student_id', $student->id)
                    ->where('role_id', $studentRole)
                    ->delete();
            }
        }

        Alert::info('Группа была успешно создана');

        return redirect()->route('platform.student.list');
    }

    /**
     * @param Student $student
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function remove(Student $student, Request $request)
    {
        $student->delete()
            ? Alert::info('Вы успешно удалили группу')
            : Alert::warning('Возникла ошибка при удалении группы');

        return redirect()->route('platform.student.list');
    }
}
