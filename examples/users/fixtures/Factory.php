<?php

namespace Examples\Users\Fixtures;

use Examples\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;

/**
 * @extends BaseFactory<User>
 */
class Factory extends BaseFactory
{
    protected $model = User::class;

    /**
     * ファクトリのデフォルト状態を指定する
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }
}
