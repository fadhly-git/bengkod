<?php

namespace App\Http\Requests\Dokter;

use Illuminate\Foundation\Http\FormRequest;

class StorePeriksaRequest extends FormRequest
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
            'catatan' => ['nullable', 'string', 'max:5000'],
            'obat_ids' => ['nullable', 'array'],
            'obat_ids.*' => ['integer', 'exists:obat,id'],
        ];
    }
}
