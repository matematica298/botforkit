<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

/**
 * Class Exchange
 * @package App\Models
 * @property integer $group_id
 * @property Carbon $date
 * @property string $order
 * @property string $old_title
 * @property string $title
 * @property string $cab
 * @property integer $teacher_id
 * @property boolean $sent
 */
class Exchange extends Model
{
    use HasFactory, AsSource, Filterable;

    /**
     * Заполняемые поля
     * @var string[]
     */
    protected $fillable = [
        'group_id',
        'date',
        'order',
        'old_title',
        'title',
        'cab',
        'teacher_id',
        'sent',
    ];

    /**
     * Мутатор для отображения даты
     * @var string[]
     */
    protected $casts = [
        'date' => 'datetime:d.m.Y'
    ];

    /**
     * Разрешенные поля для сортировке в Орхиде
     * @var string[]
     */
    protected $allowedSorts = [
        'date',
    ];

    /**
     * Получить группу от текущей замены
     * @return BelongsTo
     */
    public function group()
    {
        return $this->belongsTo('App\Models\Group');
    }

    /**
     * Получить модель преподавателя для текущей замены
     * @return BelongsTo
     */
    public function teacher()
    {
        return $this->belongsTo('App\Models\Teacher');
    }


}
