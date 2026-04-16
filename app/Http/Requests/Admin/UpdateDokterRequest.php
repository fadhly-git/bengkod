<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDokterRequest extends FormRequest
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
        $dokterParam = $this->route('dokter');
        $dokterId = is_object($dokterParam) ? $dokterParam->id : $dokterParam;

        return [
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($dokterId)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'alamat' => ['required', 'string', 'max:500'],
            'no_ktp' => ['required', 'string', 'max:20', Rule::unique('users', 'no_ktp')->ignore($dokterId)],
            'no_hp' => ['required', 'string', 'max:20'],
            'id_poli' => ['required', 'exists:poli,id'],
        ];
    }
}
