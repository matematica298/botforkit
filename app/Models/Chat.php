<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

/**
 * Class Chat
 * @package App\Models
 * @property integer $id
 * @property integer $group_id
 * @property boolean $get_mailing
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Chat extends Model
{
    use HasFactory, AsSource;

    /**
     * Заполняемые поля
     * @var string[]
     */
    protected $fillable = [
        'id',
        'group_id',
        'get_mailing',
    ];
}
