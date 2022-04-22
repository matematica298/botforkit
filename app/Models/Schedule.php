<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Lang;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

/**
 * Class Schedule
 * @package App\Models
 * @property Carbon $start
 * @property string $file
 */
class Schedule extends Model
{
    use HasFactory, AsSource, Attachable;

    /**
     * Заполняемые поля
     * @var string[]
     */
    protected $fillable = [
        'start',
        'file',
    ];

    /**
     * Получить все пары для текущего расписания
     * @return HasMany
     */
    public function lessons()
    {
        return $this->hasMany('App\Models\Lesson');
    }

    /**
     * Аксессор, меняющий отображение даты
     * @param $value
     * @return string
     */
    public function getStartAttribute($value)
    {
        $date = date_create($value);

        return $date->format('j') . ' ' . Lang::get('months.of.' . $date->format('F')) . ' ' . $date->format('Y') . ' года';
    }
}
