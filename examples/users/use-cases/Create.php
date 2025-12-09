<?php

namespace Examples\Users\UseCases;

use Examples\Models\User;

class Create
{
    /**
     * ユーザーを作成する
     *
     * @param  array{name: string}  $data
     */
    public function __invoke(array $data): User
    {
        return User::query()->create($data);
    }
}
