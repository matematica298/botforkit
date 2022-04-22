<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

/**
 * Class RoleStudent
 * @package App\Models
 * @property integer $student_id
 * @property integer $role_id
 */
class RoleStudent extends Model
{
    use HasFactory, AsSource;

    /**
     * Название таблицы в базе данных
     * @var string
     */
    public $table = 'role_student';

    /**
     * Заполняемые поля
     * @var string[]
     */
    protected $fillable = [
        'student_id',
        'role_id',
    ];
}
