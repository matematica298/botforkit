<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

/**
 * Class Teacher
 * @package App\Models
 * @property integer $id
 * @property string $name
 * @property string $position
 * @property string $email
 * @property string $shortname
 */
class Teacher extends Model
{
    use HasFactory, AsSource, Filterable;

    /**
     * Заполняемые поля
     * @var string[]
     */
    protected $fillable = [
        'name',
        'position',
        'email',
    ];

    /**
     * Мутаторы для отображения дат
     * @var string[]
     */
    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i:s',
        'updated_at' => 'datetime:d.m.Y H:i:s',
    ];

    /**
     * Поля, допустимые для сортировки в Орхиде
     * @var string[]
     */
    protected $allowedSorts = [
        'name',
    ];

    /**
     * Аксессор, сокращающий имя
     * @return string
     */
    public function getShortnameAttribute()
    {
        $name = explode(' ', $this->name);

        return $name[0] . ' ' . mb_substr($name[1], 0, 1, 'utf-8') . '.' . mb_substr($name[2], 0, 1, 'utf-8') . '.';
    }

    /**
     * Получить группу куратора
     * @return HasOne
     */
    public function group()
    {
        return $this->hasOne('App\Models\Group', 'curator_id');
    }
}
