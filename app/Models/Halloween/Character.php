<?php

namespace App\Models\Halloween;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Screen\AsSource;

/**
 * Class Character
 * @package App\Models\Halloween
 * @property string $name
 * @property string $side
 * @property string $normal_side
 * @property boolean $busy
 * @property string $image
 * @property string $description
 */
class Character extends Model
{
    use HasFactory, AsSource;

    /**
     * Заполняемые поля
     * @var string[]
     */
    protected $fillable = [
        'name',
        'image',
        'description',
        'side',
        'busy',
        'student_id'
    ];

    protected $sides = [
        'evil' => 'Отрицательный',
        'good' => 'Положительный',
        'neutral' => 'Нейтральный',
    ];

    /**
     * Мутатор для отображения дат
     * @var string[]
     */
    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i:s'
    ];

    /**
     * Название таблицы
     * @var string
     */
    public $table = 'halloween_characters';

    public function getNormalSideAttribute()
    {
        return $this->sides[$this->side];
    }

    /**
     * Получить студента
     * @return BelongsTo
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
