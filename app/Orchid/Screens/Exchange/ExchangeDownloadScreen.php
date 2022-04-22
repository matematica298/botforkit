<?php

namespace App\Orchid\Screens\Exchange;

use App\Models\Exchange;
use App\Models\Group;
use App\Models\Teacher;
use App\Modules\Auxiliary;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ExchangeDownloadScreen extends Screen
{
    /**
     * @var string
     */
    public $name = 'Загрузка замен';

    /**
     * @var string
     */
    public $description = 'Страница загрузки нового файла замен';

    /**
     * @return array
     */
    public function query(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function commandBar(): array
    {
        return [
            Link::make('Назад')
                ->icon('arrow-left')
                ->route('platform.exchange.list'),
            Button::make('Загрузить')
                ->icon('share-alt')
                ->method('downloadExchange'),
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
                    ->title('Файл замен')
                    ->required(),
                DateTimer::make('date')
                    ->title('Дата замен')
                    ->help('Необязтельно. Если не выбрано, то дата замен будет сформирована по названию файла'),
            ])
        ];
    }

    /**
     * Загрузка файла замен
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function downloadExchange(Request $request)
    {
        $data = $request->validate([
            'file' => 'required|file|mimes:xls,xlsx',
            'date' => 'nullable|date',
        ]);

        Storage::disk('public')->put('exchanges', $data['file']);

        $date = is_null($data['date'])
            ? date("Y-m-d", strtotime(substr($request->file('file')->getClientOriginalName(), 0, 8) . "20"))
            : date("Y-m-d", strtotime($data['date']))
        ;

        $reader = new Xlsx;
        $active = $reader->load($data['file'])->getActiveSheet();
        $dateExchanges = Exchange::query()->where('date', $date)->get();

        foreach($active->getRowIterator() as $row => $rowObject) {
            $value = $this->getExcelCell($active, 1, $row);

            if (is_numeric(substr($value, 0, 1))) {
                if ($this->getExcelCell($active, 1, $row + 1) === '№ пары') {
                    $possibleGroup = substr($value, 0, (strpos($value, " ") ? strpos($value, " ") : 3));
                    $possibleGroup = (strlen($possibleGroup) < 2) ? '0' . $possibleGroup : $possibleGroup;
                    $group = Auxiliary::getGroupByNumber($possibleGroup);

                    if (!$group) {
                        Alert::error('Некорректная группа: ' . $possibleGroup . ' в строке ' . $row);

                        return redirect()->back();
                    }
                } elseif ((substr($value, 0, 2) % 2) != 0) {
                    if (is_null($this->getExcelCell($active, 4, $row))) {
                        $change = [
                            'group_id' => $group->id,
                            'date' => $date,
                            'order' => ceil(substr($value, 0, 1) / 2),
                            'old_title' => ($this->getExcelCell($active, 2, $row) != "дома")
                                ? $this->getExcelCell($active, 2, $row)
                                : null,
                            'title' => null,
                            "cab" => $this->getExcelCell($active, 5, $row),
                            "teacher_id" => ($this->getExcelCell($active, 3, $row) != "дома")
                                ? $this->getTeacherID($this->getExcelCell($active, 3, $row))
                                : null
                        ];
                    } else {
                        $change = [
                            "group_id" => $group->id,
                            "date" => $date,
                            "order" => ceil(substr($value, 0, 1) / 2),
                            "old_title" => $this->getExcelCell($active, 2, $row),
                            "title" => $this->getExcelCell($active, 4, $row),
                            "cab" => $this->getExcelCell($active, 5, $row),
                            "teacher_id" => $this->getTeacherID($this->getExcelCell($active, 3, $row))
                        ];
                    }

                    $exchange = $dateExchanges
                        ->where('group_id', $group->id)
                        ->where('order', ceil(substr($value, 0, 1) / 2))
                        ->first();

                    if ($exchange) {
                        $exchange->fill($change)->save();
                    } else {
                        Exchange::query()->create($change);
                    }
                }
            }
        }

        Alert::info('Файл замен был успешно загружен');

        return redirect()->route('platform.exchange.view', ['date' => $date]);
    }

    /**
     * Получение значения поля
     * @param $object
     * @param $column
     * @param $row
     * @return mixed
     */
    protected function getExcelCell($object, $column, $row)
    {
        return $object->getCellByColumnAndRow($column, $row)->getValue();
    }

    /**
     * Получить ID преподавателя по фамилии
     * @param null $rawTeacher
     * @return mixed|null|void
     */
    protected function getTeacherID($rawTeacher = null)
    {
        if (!$rawTeacher) {
            return null;
        }

        $teacherParts = explode(' ', str_replace('.', ' ', $rawTeacher));
        $teacher = Teacher::query()
            ->where('name', 'like', implode('%', array_slice($teacherParts, 0, 3)) . '%')
            ->get();

        if ($teacher->isNotEmpty()) {
            return $teacher->first()->id;
        } else {
            foreach(Teacher::all() as $oneTeacher) {
                $surname = explode(' ', $oneTeacher->name)[0];
                if ((levenshtein($teacherParts[0], $surname) / strlen($surname)) < 0.25) {
                    return $oneTeacher->id;
                }
            }
        }

        return null;
    }
}
