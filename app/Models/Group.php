<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

/**
 * Class Group
 * @package App\Models
 * @property number $id
 * @property string $number
 * @property number $curator_id
 * @property string $course
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Teacher $curator
 */
class Group extends Model
{
    use HasFactory, AsSource, Filterable;

    /**
     * Заполняемые
     * @var string[]
     */
    protected $fillable = [
        'number',
        'curator_id',
        'course',
    ];

    /**
     * Мутатор для отображения дат
     * @var string[]
     */
    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i:s',
        'updated_at' => 'datetime:d.m.Y H:i:s',
    ];

    /**
     * Допустимые для сортировки поля в Орхиде
     * @var string[]
     */
    protected $allowedSorts = [
        'number',
        'course',
    ];

    /**
     * Получить все замены для текущей группы
     * @return HasMany
     */
    public function exchanges()
    {
        return $this->hasMany('App\Models\Exchange');
    }

    /**
     * Получить куратора текущей группы
     * @return BelongsTo
     */
    public function curator()
    {
        return $this->belongsTo('App\Models\Teacher', 'curator_id');
    }
}
