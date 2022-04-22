<?php

namespace App\Orchid\Screens\Lesson;

use App\Models\Lesson;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class LessonEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Редактировать пару';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Пары колледжа';

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * @param Lesson $lesson
     * @return array
     */
    public function query(Lesson $lesson): array
    {
        $this->exists = $lesson->exists;

        if ($this->exists) {
            $this->name = 'Редактировать пару';
        }

        return [
            'lesson' => $lesson
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
            Button::make('Добавить пару')
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
                DateTimer::make('lesson.schedule_id')
                    ->title('ID расписания'),
            ])
        ];
    }

    /**
     * @param Lesson $lesson
     * @param Request $request
     * @return RedirectResponse
     */
    public function createOrUpdate(Lesson $lesson, Request $request)
    {
        $lesson->fill($request->get('lesson'))->save();

        Alert::info('Пара была успешно добавлена');

        return redirect()->route('platform.lesson.list');
    }

    /**
     * @param Lesson $lesson
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function remove(Lesson $lesson, Request $request)
    {
        $lesson->delete()
            ? Alert::info('Вы успешно добавили пару')
            : Alert::warning('Возникла ошибка при удалении пары');

        return redirect()->route('platform.lesson.list');
    }
}
