<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreDokterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'alamat' => ['required', 'string', 'max:500'],
            'no_ktp' => ['required', 'string', 'max:20', 'unique:users,no_ktp'],
            'no_hp' => ['required', 'string', 'max:20'],
            'id_poli' => ['required', 'exists:poli,id'],
        ];
    }
}
