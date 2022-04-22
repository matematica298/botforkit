<?php

namespace App\Models;

use App\Models\Halloween\Character;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Orchid\Screen\AsSource;

/**
 * Class Student
 * @package App\Models
 * @property integer $id
 * @property string $name
 * @property string $surname
 * @property string $nickname
 * @property integer $group_id
 * @property integer $reputation
 * @property string $description
 * @property bool $get_mailing
 * @property Collection $roles
 * @property Group $group
 */
class Student extends Model
{
    use HasFactory, AsSource;

    /**
     * Заполняемые поля
     * @var string[]
     */
    protected $fillable = [
        'id',
        'name',
        'surname',
        'nickname',
        'group_id',
        'reputation',
        'description',
        'get_mailing',
    ];

    /**
     * Получить список ролей студента
     * @return BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\StudentRole', 'role_student', 'student_id', 'role_id');
    }

    /**
     * Получить группу студента
     * @return BelongsTo
     */
    public function group()
    {
        return $this->belongsTo('App\Models\Group');
    }

    /**
     * @return HasOne
     */
    public function character()
    {
        return $this->hasOne(Character::class);
    }
}
