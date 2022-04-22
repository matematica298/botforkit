<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

/**
 * Class Subject
 * @package App\Models
 * @property integer $id
 * @property string $name
 * @property string $normal_name
 */
class Subject extends Model
{
    use HasFactory, AsSource;

    /**
     * Заполняемые поля
     * @var string[]
     */
    protected $fillable = [
        'name',
        'normal_name',
    ];

    /**
     * В таблице отстствуют тампштампы
     * @var bool
     */
    public $timestamps = false;
}
