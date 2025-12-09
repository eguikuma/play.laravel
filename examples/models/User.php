<?php

namespace Examples\Models;

use Examples\Users\Fixtures\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /** @use HasFactory<Factory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
    ];

    /**
     * ファクトリを指定する
     */
    protected static function newFactory(): Factory
    {
        return Factory::new();
    }
}
