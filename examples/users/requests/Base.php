<?php

namespace Examples\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Base extends FormRequest
{
    /**
     * バリデーションメッセージを指定する
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => '名前を入力してください',
            'name.max' => '名前は255文字以内で入力してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => '有効なメールアドレスを入力してください',
            'email.max' => 'メールアドレスは255文字以内で入力してください',
            'email.unique' => 'このメールアドレスは既に登録されています',
        ];
    }
}
