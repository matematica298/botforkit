<?php

namespace App\Orchid\Screens\Exchange;

use App\Models\Exchange;
use App\Orchid\Layouts\Exchange\ExchangeListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class ExchangeListScreen extends Screen
{
    /**
     * Заголовок страницы
     * @var string
     */
    public $name = 'Список замен';

    /**
     * Описание страницы
     * @var string
     */
    public $description = 'Последние 30 загруженных файлов';

    /**
     * Формирование массива данных для отображения
     * @return array
     */
    public function query(): array
    {
        return [
            'exchanges' => Exchange::query()
                ->select('date')
                ->distinct('date')
                ->orderBy('date', 'desc')
                ->limit(30)
                ->get()
        ];
    }

    /**
     * Панель кнопок
     * @return array
     */
    public function commandBar(): array
    {
        return [
            Link::make('Загрузить')
                ->icon('share-alt')
                ->route('platform.exchange.download'),
        ];
    }

    /**
     * Используемые блоки для отображения
     * @return string[]
     */
    public function layout(): array
    {
        return [
            ExchangeListLayout::class
        ];
    }
}
