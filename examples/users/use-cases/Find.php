<?php

namespace Examples\Users\UseCases;

use Examples\Models\User;

class Find
{
    /**
     * ユーザーを取得する
     */
    public function __invoke(int $id): ?User
    {
        return User::find($id, ['id', 'name', 'email', 'created_at', 'updated_at']);
    }
}
