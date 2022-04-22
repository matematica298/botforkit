<?php

namespace App\Orchid\Screens\Exchange;

use App\Models\Exchange;
use App\Modules\Timesheet;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Lang;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class ExchangeViewScreen extends Screen
{
    /**
     * @var string
     */
    public $name = 'Просмотр замен';

    /**
     * @var string
     */
    public $description = 'Загруженные замены на';

    /**
     * @param string $date
     * @return Collection[]
     * @throws Exception
     */
    public function query(string $date): array
    {
        $date = new Carbon($date);
        $exchanges = Exchange::with(['group', 'teacher'])
            ->where('date', $date->format('Y-m-d'))
            ->get()
            ->each(fn($item) => $item->groupNum = $item->group->number);

        $this->description = $this->description . ' ' . $date->format('j') . ' ' . Lang::get('months.of.' . $date->format('F')) . ' ' . $date->format('Y') . ' года (' . Lang::get('weekdays.' . $date->format('l')) . ', ' . ((($date->format('W') % 2) == 0) ? "чётная" : "нечётная") . ')';

        return [
            'exchanges' => $exchanges->groupBy('groupNum')
        ];
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
            Button::make('Рассылка')
                ->icon('social-vkontakte')
                ->method('sendChanges'),
        ];
    }

    /**
     * @return array
     */
    public function layout(): array
    {
        return [
            Layout::view('orchid.exchange.view')
        ];
    }

    public function sendChanges()
    {
//        Timesheet::sendChanges();
    }
}
