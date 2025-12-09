<?php

namespace Examples\Users\UseCases;

use Examples\Models\User;

class Update
{
    /**
     * ユーザーを更新する
     *
     * @param  array{name?: string, email?: string}  $data
     */
    public function __invoke(int $id, array $data): ?User
    {
        $user = User::find($id, ['id', 'name', 'email']);

        if ($user === null) {
            return null;
        }

        $user->update($data);
        $user->refresh();

        return $user;
    }
}
