<?php

namespace App\Orchid\Screens\Group;

use App\Models\Group;
use App\Orchid\Layouts\Group\GroupListLayout;
use Exception;
use Illuminate\Http\RedirectResponse;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class GroupListScreen extends Screen
{
    /**
     * @var string
     */
    public $name = 'Список групп колледжа';

    /**
     * @var string
     */
    public $description = 'Группы колледжа';

    /**
     * @return array
     */
    public function query(): array
    {
        return [
            'groups' => Group::filters()->defaultSort('course')->get()
        ];
    }

    /**
     * @return array
     */
    public function commandBar(): array
    {
        return [
            Button::make('Поднять курсы')
                ->icon('arrow-up-circle')
                ->confirm('Вы уверены? Это переведёт все группы на старший курс.')
                ->method('upCourses'),
            Link::make('Добавить группу')
                ->icon('plus')
                ->route('platform.group.edit'),
        ];
    }

    /**
     * @return string[]
     */
    public function layout(): array
    {
        return [
            GroupListLayout::class
        ];
    }

    /**
     * Поднять курсы
     * @return RedirectResponse
     * @throws Exception
     */
    public function upCourses()
    {
        Group::query()->where('course', '4')->delete();
        $groups = Group::all();

        foreach ($groups as $group) {
            if (strlen($group->number) < 3) {
                if ('3' == $group->course) {
                    $group->delete();
                    continue;
                }
            } else {
                $group->number = (((integer)substr($group->number, 0, 1)) + 1) . substr($group->number, 1);
            }

            $group->course++;
            $group->save();
        }

        return redirect()->route('platform.group.list');
    }
}
