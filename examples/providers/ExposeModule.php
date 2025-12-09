<?php

namespace Examples\Providers;

use Examples\Components\Welcome;
use Examples\Users\Components\Cards;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class ExposeModule extends ServiceProvider
{
    /**
     * サービス起動時に事前処理を行う
     *
     * ※ なるべくコアのコードをプレーンに保ちたく'examples'ディレクトリに処理を切り出しており、本来は不要と思われる処理を追加している
     */
    public function boot(): void
    {
        /**
         * 独自ディレクトリに配置されたマイグレーションを明示的に登録する
         * デフォルトでは'database/migrations'配下のみ検索される
         */
        $this->loadMigrationsFrom(base_path('examples/migrations'));

        /**
         * 独自ディレクトリに配置されたLivewireコンポーネントを明示的に登録する
         * デフォルトでは'App\Livewire'配下のみ検索される
         *
         * @see vendor/livewire/livewire/src/Mechanisms/ComponentRegistry.php
         */
        Livewire::component('examples.components.welcome', Welcome::class);
        Livewire::component('examples.users.components.cards', Cards::class);

        /**
         * 独自ディレクトリに配置されたビューを明示的に登録する
         * デフォルトでは'resources/views'配下のみ検索される
         *
         * @see vendor/laravel/framework/src/Illuminate/View/FileViewFinder.php
         * @see config/view.php 'paths'
         */
        View::addNamespace('examples', base_path('examples/views'));

        /**
         * 独自ディレクトリに配置されたルートを明示的に登録する
         * デフォルトでは'routes/web.php'等のみ読み込まれる
         *
         * @see vendor/laravel/framework/src/Illuminate/Routing/Router.php
         * @see bootstrap/app.php 'withRouting'
         */
        Route::middleware('web')
            ->group(base_path('examples/routes.php'));
    }
}
