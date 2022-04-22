<?php

namespace App\Models;

use Orchid\Platform\Models\User as Authenticatable;

/**
 * Class User
 * @package App\Models
 * @property integer $id
 * @property string $name
 * @property string $email
 */
class User extends Authenticatable
{
    /**
     * Заполняемые поля
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'permissions',
    ];

    /**
     * Скрытые поля
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
    ];

    /**
     * Мутаторы для отображения дат и других ключей
     * @var array
     */
    protected $casts = [
        'permissions'          => 'array',
        'email_verified_at'    => 'datetime',
    ];

    /**
     * Допустимые поля для фильтрации в Орхиде
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'name',
        'email',
        'permissions',
    ];

    /**
     * Допустимые поля для сортировке в Орхиде
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'email',
        'updated_at',
        'created_at',
    ];
}
