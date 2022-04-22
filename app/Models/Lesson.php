<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Lang;
use Orchid\Screen\AsSource;

/**
 * Class Lesson
 * @package App\Models
 * @property integer $id
 * @property integer $schedule_id
 * @property integer $group_id
 * @property string $weekday
 * @property string $order
 * @property integer $subject_id
 * @property integer $teacher_id
 * @property string $cab
 * @property string $even
 */
class Lesson extends Model
{
    use HasFactory, AsSource;

    /**
     * Заполняемые поля
     * @var string[]
     */
    protected $fillable = [
        'id',
        'schedule_id',
        'group_id',
        'weekday',
        'order',
        'subject_id',
        'teacher_id',
        'cab',
        'even',
    ];

    /**
     * Получить расписание для текущей пары
     * @return BelongsTo
     */
    public function schedule()
    {
        return $this->belongsTo('App\Models\Schedule');
    }

    /**
     * Получить модель группы от текущей пары
     * @return BelongsTo
     */
    public function group()
    {
        return $this->belongsTo('App\Models\Group');
    }

    /**
     * Получить модель предмета от текущей пары
     * @return BelongsTo
     */
    public function subject()
    {
        return $this->belongsTo('App\Models\Subject');
    }

    /**
     * Получить преподавателя текущей пары
     * @return BelongsTo
     */
    public function teacher()
    {
        return $this->belongsTo('App\Models\Teacher');
    }

    /**
     * Аксессор, меняющий отображение дня недели пары
     * @param $value
     * @return string
     */
    public function getWeekdayAttribute($value)
    {
        return Lang::get('weekdays.num' . $value);
    }
}
