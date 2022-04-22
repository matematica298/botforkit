<?php

namespace App\Orchid\Screens\Schedule;

use App\Models\Group;
use App\Models\Lesson;
use App\Models\Schedule;
use App\Models\Subject;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ScheduleDownloadScreen extends Screen
{
    /**
     * Соответствеия дней недели и номеров
     */
    const WEEKDAYS = [
        "воскресенье"   => 1,
        "понедельник"   => 2,
        "вторник"       => 3,
        "среда"         => 4,
        "четверг"       => 5,
        "пятница"       => 6,
        "суббота"       => 7,
    ];

    /**
     * @var string
     */
    public $name = 'Редактировать расписание';

    /**
     * @var string
     */
    public $description = 'Расписание';

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * @param Schedule $schedule
     * @return array
     */
    public function query(Schedule $schedule): array
    {
        $this->exists = $schedule->exists;

        if ($this->exists) {
            $this->name = 'Редактировать расписание';
        }

        $schedule->load('attachment');

        return [
            'schedule' => $schedule
        ];
    }

    /**
     * @return array
     */
    public function commandBar(): array
    {
        return [
            Button::make('Добавить расписание')
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
                Input::make('file')
                    ->type('file')
                    ->title('Файл расписания'),
                DateTimer::make('start')
                    ->title('Начало действия расписания'),
            ])
        ];
    }

    /**
     * @param Schedule $schedule
     * @param Request $request
     * @return RedirectResponse
     */
    public function createOrUpdate(Schedule $schedule, Request $request)
    {
        $data = $request->validate([
            'file' => 'required|file',
            'start' => 'nullable|date',
        ]);

        # Сохраняем XLS документ
        Storage::disk('public')->put('schedules', $data['file']);

        # Создаём новое расписание
        $schedule = Schedule::query()->create([
            'start' => $data['start'] ?? Carbon::now(),
        ]);

        # открываем ридер файла
        $reader = new Xlsx;
        $spreadsheet = $reader->load($data['file']);
        $active = $spreadsheet->getActiveSheet();

        for ($column = 4; $active->getCellByColumnAndRow($column, 2)->getValue() != null; $column++) {
            $currentGroup = Group::query()->where('number', $active->getCellByColumnAndRow($column, 2)->getValue())->get()->first();

            if (!$currentGroup) {
                Alert::warning('Не найдена группа ' . $active->getCellByColumnAndRow($column, 2)->getValue() . ' в системе');

                break;
            }

            for ($row = 3; $row < 62; $row = $row + 2) {
                # здесь получаем значение дня недели
                if (!is_null($active->getCellByColumnAndRow(1, $row)->getValue())) {
                    $day = self::WEEKDAYS[mb_strtolower($active->getCellByColumnAndRow(1, $row)->getValue())];
                }

                if ($active->getCellByColumnAndRow($column, $row)->getValue() != null) {
                    $subject = explode("//", $active->getCellByColumnAndRow($column, $row)->getValue());

                    if (count($subject) > 1) {
                        if ($subject[0] !== '') {
                            Lesson::query()->create([
                                'schedule_id' => $schedule->id,
                                'group_id' => $currentGroup->id,
                                'weekday' => $day,
                                'order' => ceil($active->getCellByColumnAndRow(2, $row)->getValue() / 2),
                                'subject_id' => $this->getSubjectName($subject[0]),
                                'cab' => $this->getSubjectCab($subject[0]),
                                'even' => 'odd',
                            ]);
                        }

                        if ($subject[1] !== '') {
                            Lesson::query()->create([
                                'schedule_id' => $schedule->id,
                                'group_id' => $currentGroup->id,
                                'weekday' => $day,
                                'order' => ceil($active->getCellByColumnAndRow(2, $row)->getValue() / 2),
                                'subject_id' => $this->getSubjectName($subject[1]),
                                'cab' => $this->getSubjectCab($subject[1]),
                                'even' => 'even',
                            ]);
                        }
                    } else {
                        Lesson::query()->create([
                            'schedule_id' => $schedule->id,
                            'group_id' => $currentGroup->id,
                            'weekday' => $day,
                            'order' => ceil($active->getCellByColumnAndRow(2, $row)->getValue() / 2),
                            'subject_id' => $this->getSubjectName($subject[0]),
                            'cab' => $this->getSubjectCab($subject[0]),
                            'even' => 'nvm',
                        ]);
                    }
                }
            }
        }

        Alert::info('Расписание было успешно добавлено');

        return redirect()->route('platform.schedule.list');
    }

    /**
     * @param Schedule $schedule
     * @return RedirectResponse
     * @throws Exception
     */
    public function remove(Schedule $schedule)
    {
        $schedule->delete()
            ? Alert::info('Вы успешно добавили предмет')
            : Alert::warning('Возникла ошибка при удалении предмета');

        return redirect()->route('platform.schedule.list');
    }

    /**
     * @param string $rawSubject
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed
     */
    protected function getSubjectName(string $rawSubject)
    {
        $clearedSubject = strpos($rawSubject, '(') ? substr($rawSubject, 0, strpos($rawSubject, '(')) : $rawSubject;
        $searchSubject = mb_substr($clearedSubject, 0, mb_strlen($clearedSubject, 'utf-8') - 1, 'utf-8');
        $possibleSubjects = Subject::query()->where('name', 'like', $searchSubject.'%')->get();

        if ($possibleSubjects->isNotEmpty()) {
            return $possibleSubjects->first()->id;
        }

        $newSubject = Subject::query()->create([
            'name' => $clearedSubject,
            'normal_name' => $clearedSubject,
        ]);

        return $newSubject->id;
    }

    /**
     * @param string $rawSubject
     * @return false|string|null
     */
    protected function getSubjectCab(string $rawSubject)
    {
        if (!strpos($rawSubject, '(')) {
            return null;
        }

        $subject = explode('/', $rawSubject);

        if (count($subject) > 1) {
            $cab1 = substr($subject[0], strrpos($subject[0], '(') + 1, strrpos($subject[0], ')') - strrpos($subject[0], '(') - 1);
            $cab2 = substr($subject[1], strrpos($subject[1], '(') + 1, strrpos($subject[1], ')') - strrpos($subject[1], '(') - 1);

            return (str_replace(' ', '', $cab1) . '/' . str_replace(' ', '', $cab2));
        }

        return substr($subject[0], strrpos($subject[0], '(') + 1, strrpos($subject[0], ')') - strrpos($subject[0], '(') - 1);
    }
}
