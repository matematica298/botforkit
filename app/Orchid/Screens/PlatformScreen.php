<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    /**
     * @var string
     */
    public $name = 'Сырок';

    /**
     * @var string
     */
    public $description = 'Панель управления';

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
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): array
    {
        return [];
    }
}
