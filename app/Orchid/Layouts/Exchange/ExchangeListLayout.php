<?php

namespace App\Orchid\Layouts\Exchange;

use Illuminate\Support\Facades\Lang;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ExchangeListLayout extends Table
{
    /**
     * @var string
     */
    protected $target = 'exchanges';

    /**
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::set('date', 'Дата замены')
                ->render(function($exchange){
                    return Link::make($exchange->date->format('j') . ' ' . Lang::get('months.of.' . $exchange->date->format('F')) . ' ' . $exchange->date->format('Y') . ' года')
                        ->route('platform.exchange.view', $exchange->date->format('Y-m-d'));
                }),
            TD::set('weekday', 'День недели')
                ->render(function($exchange){
                    return Lang::get('weekdays.' . $exchange->date->format('l'));
                }),
        ];
    }
}
