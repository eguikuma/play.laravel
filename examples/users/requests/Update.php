<?php

namespace Examples\Users\Requests;

use Illuminate\Validation\Rule;

class Update extends Base
{
    /**
     * バリデーションルールを指定する
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(?int $exclude = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($exclude ?? $this->route('id')),
            ],
        ];
    }
}
