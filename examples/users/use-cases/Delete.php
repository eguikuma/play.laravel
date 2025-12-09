<?php

namespace Examples\Users\UseCases;

use Examples\Models\User;

class Delete
{
    /**
     * ユーザーを削除する
     */
    public function __invoke(int $id): bool
    {
        $user = User::find($id, ['id']);

        if ($user === null) {
            return false;
        }

        return (bool) $user->delete();
    }
}
