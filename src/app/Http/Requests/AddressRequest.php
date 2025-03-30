<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function rules(): array
    {
        $routeName = \Route::currentRouteName();

        $rules = [
            'postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address'     => 'required|string|max:255',
            'building'    => 'required|string|max:255',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        if ($routeName === 'profile.update') {
            $rules['name'] = 'required|string|max:255';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required'         => 'お名前を入力してください',
            'postal_code.required'  => '郵便番号を入力してください',
            'postal_code.regex'     => '郵便番号はハイフンありの8文字形式で入力してください（例: 123-4567）',
            'address.required'      => '住所を入力してください',
            'building.required'     => '建物名を入力してください',
            'image.image'           => '画像ファイルを選択してください',
            'image.mimes'           => '画像はjpeg、png、jpg形式である必要があります',
            'image.max'             => '画像サイズは2MB以内にしてください',
        ];
    }
}

