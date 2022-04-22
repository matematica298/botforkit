<?php

namespace App\Orchid\Screens\Exchange;

use App\Models\Exchange;
use App\Models\Group;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class ExchangeEditScreen extends Screen
{
    /**
     * @var string
     */
    public $name = 'Редактирование замены';

    /**
     * @var string
     */
    public $description = 'Замена на ';

    /**
     * @param Exchange $exchange
     * @return Exchange[]
     */
    public function query(Exchange $exchange): array
    {
        $this->description .= $exchange->date->format('d.m.Y');

        return [
            'exchange' => $exchange
        ];
    }

    /**
     * @return array
     */
    public function commandBar(): array
    {
        return [
            Button::make('Обновить замену')
                ->icon('note')
                ->method('update'),

            Button::make('Удалить замену')
                ->icon('trash')
                ->method('remove'),
        ];
    }

    /**
     * @return array
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                Relation::make('exchange.group_id')
                    ->fromModel(Group::class, 'number')
                    ->title('Группа'),
                DateTimer::make('exchange.date')
                    ->title('Дата замены')
                    ->format('Y-m-d'),
                Select::make('exchange.order')
                    ->options([
                        '1' => 'Первая пара (9:00 - 10:35)',
                        '2' => 'Вторая пара (11:05 - 12:40)',
                        '3' => 'Третья пара (13:10 - 14:45)',
                        '4' => 'Четвертая пара (14:55 - 16:35)',
                        '5' => 'Пятая пара (16:40 - 18:15)',
                    ])
                    ->title('Номер пары'),
                Input::make('exchange.old_title')
                    ->title('Заменяемый предмет'),
                Input::make('exchange.title')
                    ->title('Новый предмет'),
                Input::make('exchange.cab')
                    ->title('Кабинет'),
                Relation::make('exchange.teacher_id')
                    ->fromModel(Teacher::class, 'name')
                    ->title('Заменяющий преподаватель'),
                CheckBox::make('exchange.sent')
                    ->title('Отправлена ли данная замена в рассылке')
                    ->disabled(),
            ])
        ];
    }

    public function update(Exchange $exchange, Request $request)
    {
        $exchange->fill($request->get('exchange'))->save();

        Alert::info('Замена была успешно обновлена');

        return redirect()->route('platform.exchange.view', ['date' => $exchange->date->format('Y-m-d')]);
    }

    public function remove(Exchange $exchange, Request $request)
    {
        $exchange->delete()
            ? Alert::info('Замена успешно удалена')
            : Alert::warning('Возникла ошибка при удалении замены');

        return redirect()->route('platform.exchange.view', ['date' => $exchange->date->format('Y-m-d')]);
    }
}
