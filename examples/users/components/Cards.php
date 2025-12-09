<?php

namespace Examples\Users\Components;

use Examples\Users\Requests;
use Examples\Users\UseCases;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('examples::layouts.app')]
class Cards extends Component
{
    public string $search = '';

    public ?int $deleting = null;

    public ?int $modifying = null;

    public string $name = '';

    public string $email = '';

    /**
     * ユーザー一覧を取得する
     *
     * @return Collection<int, \Examples\Models\User>
     */
    #[Computed]
    public function users(): Collection
    {
        return app(UseCases\Get::class)($this->search ?: null);
    }

    /**
     * ユーザー名の頭文字を取得する
     */
    public function avatar(string $name): string
    {
        return mb_substr($name, 0, 1);
    }

    /**
     * ユーザーを作成する
     */
    public function create(): void
    {
        $request = new Requests\Create;
        $this->validate($request->rules(), $request->messages());

        app(UseCases\Create::class)([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $this->reset('name', 'email');
        $this->resetValidation();

        $this->dispatch('created');
        $this->dispatch('notify', type: 'success', message: '作成しました');
    }

    /**
     * ユーザーの変更を開始する
     */
    public function modify(int $id): void
    {
        $user = app(UseCases\Find::class)($id);

        if ($user !== null) {
            $this->resetValidation();
            $this->modifying = $id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->dispatch('modifying');
        }
    }

    /**
     * ユーザーを更新する
     */
    public function update(): void
    {
        if ($this->modifying === null) {
            return;
        }

        $request = new Requests\Update;
        $this->validate($request->rules($this->modifying), $request->messages());

        app(UseCases\Update::class)($this->modifying, [
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $this->modifying = null;
        $this->reset('name', 'email');

        $this->dispatch('notify', type: 'success', message: '保存しました');
    }

    /**
     * 編集をキャンセルする
     */
    public function revert(): void
    {
        $this->modifying = null;
        $this->reset('name', 'email');
        $this->resetValidation();
    }

    /**
     * 削除するユーザーを選択する
     */
    public function select(int $id): void
    {
        $this->deleting = $id;
    }

    /**
     * 削除するユーザーをキャンセルする
     */
    public function cancel(): void
    {
        $this->deleting = null;
    }

    /**
     * ユーザーを削除する
     */
    public function delete(int $id): void
    {
        app(UseCases\Delete::class)($id);

        $this->deleting = null;

        $this->dispatch('notify', type: 'success', message: '削除しました');
    }

    /**
     * テンプレートを描画する
     */
    public function render(): View
    {
        return view('examples::users');
    }
}
