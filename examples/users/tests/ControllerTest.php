<?php

namespace Examples\Users\Tests;

use Examples\Models\User;
use Examples\Users\Requests;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    #[Test]
    public function すべてのユーザーを取得できること(): void
    {
        $users = User::factory()->count(3)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(3, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'email'],
            ],
        ]);
        foreach ($users as $user) {
            $response->assertJsonFragment([
                'id' => $user->id,
                'name' => $user->name,
            ]);
        }
    }

    #[Test]
    public function 特定のユーザーを取得できること(): void
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
        ]);
    }

    #[Test]
    public function 存在しないidの場合はnullが返ること(): void
    {
        $response = $this->getJson('/api/users/9999');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => null,
        ]);
    }

    #[Test]
    public function ユーザーを作成できること(): void
    {
        $expected = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
        ];
        $this->mock(Requests\Create::class, function ($mock) use ($expected) {
            $mock->shouldReceive('validated')->andReturn($expected);
        });

        $response = $this->postJson('/api/users', $expected);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'data' => null,
        ]);
        $this->assertDatabaseHas('users', [
            'name' => $expected['name'],
            'email' => $expected['email'],
        ]);
    }

    #[Test]
    #[DataProvider('invalidPostBodies')]
    public function バリデーションエラーの場合はユーザー作成が失敗すること(?string $name, ?string $email): void
    {
        $response = $this->postJson('/api/users', ['name' => $name, 'email' => $email]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['name', 'email']);
    }

    /**
     * @return array<string, array<string|null>>
     */
    public static function invalidPostBodies(): array
    {
        return [
            '空文字の場合' => ['', ''],
            'nullの場合' => [null, null],
            '最大文字数を超える場合' => [str_repeat('a', 256), str_repeat('b', 256)],
        ];
    }

    #[Test]
    public function ユーザーを更新できること(): void
    {
        $user = User::factory()->create();
        $expected = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
        ];
        $this->mock(Requests\Update::class, function ($mock) use ($expected) {
            $mock->shouldReceive('validated')->andReturn($expected);
        });

        $response = $this->putJson("/api/users/{$user->id}", $expected);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => [
                'id' => $user->id,
                'name' => $expected['name'],
                'email' => $expected['email'],
            ],
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $expected['name'],
            'email' => $expected['email'],
        ]);
    }

    #[Test]
    public function 存在しないユーザーの場合はエラーが返ること(): void
    {
        $response = $this->putJson('/api/users/9999', [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    #[DataProvider('invalidPutBodies')]
    public function バリデーションエラーの場合はユーザー更新が失敗すること(?string $name, ?string $email): void
    {
        $user = User::factory()->create();

        $response = $this->putJson("/api/users/{$user->id}", ['name' => $name, 'email' => $email]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['name', 'email']);
    }

    /**
     * @return array<string, array<string|null>>
     */
    public static function invalidPutBodies(): array
    {
        return [
            '空文字の場合' => ['', ''],
            'nullの場合' => [null, null],
            '最大文字数を超える場合' => [str_repeat('a', 256), str_repeat('b', 256)],
        ];
    }

    #[Test]
    public function ユーザーを削除できること(): void
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    #[Test]
    public function 存在しないユーザーの削除時にエラーが返ること(): void
    {
        $response = $this->deleteJson('/api/users/9999');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
