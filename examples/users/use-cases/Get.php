<?php

namespace Examples\Users\UseCases;

use Examples\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class Get
{
    /**
     * ユーザー一覧を取得する
     *
     * @return Collection<int, User>
     */
    public function __invoke(?string $search = null): Collection
    {
        return User::query()
            ->select(['id', 'name', 'email'])
            ->when($search, function (Builder $builder) use ($search) {
                $escaped = str_replace(['%', '_'], ['\\%', '\\_'], $search);

                $builder->where(function (Builder $builder) use ($escaped) {
                    $builder->where('name', 'like', "%{$escaped}%")
                        ->orWhere('email', 'like', "%{$escaped}%");
                });
            })
            ->get();
    }
}
