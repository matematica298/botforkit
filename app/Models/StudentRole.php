<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

/**
 * Class StudentRole
 * @package App\Models
 * @property integer $id
 * @property string $title
 * @property string $short_title
 */
class StudentRole extends Model
{
    use HasFactory, AsSource;

    /**
     * Заполняемые поля
     * @var string[]
     */
    protected $fillable = [
        'title',
        'short_title',
    ];

    /**
     * У таблицы отсутствуют таймпштампы
     * @var bool
     */
    public $timestamps = false;
}
