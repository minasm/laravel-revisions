<?php

namespace Neurony\Revisions\Tests\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int $id
 */
class User extends Authenticatable
{
    /**
     * The database table.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}
