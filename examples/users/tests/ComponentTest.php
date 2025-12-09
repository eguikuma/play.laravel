<?php

namespace Examples\Users\Tests;

use Examples\Models\User;
use Examples\Users\Components\Cards;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ComponentTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    #[Test]
    public function ユーザー一覧が表示されること(): void
    {
        $users = User::factory()->count(3)->create();

        Livewire::test(Cards::class)
            ->assertStatus(Response::HTTP_OK)
            ->assertSee($users->first()->name);
    }

    #[Test]
    public function ユーザーを検索できること(): void
    {
        User::factory()->create(['name' => '田中 太郎', 'email' => 'tanaka@example.com']);
        User::factory()->create(['name' => '中田 郎太', 'email' => 'nakata@example.com']);

        Livewire::test(Cards::class)
            ->set('search', '田中')
            ->assertSee('田中 太郎')
            ->assertDontSee('中田 郎太');
    }

    #[Test]
    public function ユーザーを作成できること(): void
    {
        $name = $this->faker->name;
        $email = $this->faker->unique()->safeEmail;

        Livewire::test(Cards::class)
            ->set('name', $name)
            ->set('email', $email)
            ->call('create');

        $this->assertDatabaseHas('users', ['name' => $name, 'email' => $email]);
    }

    #[Test]
    public function 空の場合、ユーザーを作成できないこと(): void
    {
        Livewire::test(Cards::class)
            ->set('name', '')
            ->set('email', '')
            ->call('create')
            ->assertHasErrors(['name', 'email']);
    }

    #[Test]
    public function ユーザーを更新できること(): void
    {
        $user = User::factory()->create();
        $name = $this->faker->name;
        $email = $this->faker->unique()->safeEmail;

        Livewire::test(Cards::class)
            ->call('modify', $user->id)
            ->assertSet('modifying', $user->id)
            ->assertSet('name', $user->name)
            ->assertSet('email', $user->email)
            ->set('name', $name)
            ->set('email', $email)
            ->call('update');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $name,
            'email' => $email,
        ]);
    }

    #[Test]
    public function ユーザーの更新をキャンセルできること(): void
    {
        $user = User::factory()->create();

        Livewire::test(Cards::class)
            ->call('modify', $user->id)
            ->assertSet('modifying', $user->id)
            ->call('revert')
            ->assertSet('modifying', null)
            ->assertSet('name', '')
            ->assertSet('email', '');
    }

    #[Test]
    public function 空の場合、ユーザーを更新できないこと(): void
    {
        $user = User::factory()->create();

        Livewire::test(Cards::class)
            ->call('modify', $user->id)
            ->set('name', '')
            ->set('email', '')
            ->call('update')
            ->assertHasErrors(['name', 'email']);
    }

    #[Test]
    public function ユーザーを削除できること(): void
    {
        $user = User::factory()->create();

        Livewire::test(Cards::class)
            ->call('delete', $user->id);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
