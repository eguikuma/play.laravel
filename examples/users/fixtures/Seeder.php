<?php

namespace Examples\Users\Fixtures;

use Examples\Models\User;
use Illuminate\Database\Seeder as BaseSeeder;

class Seeder extends BaseSeeder
{
    /**
     * シードを実行する
     */
    public function run(): void
    {
        User::factory()
            ->count(10)
            ->create();
    }
}
