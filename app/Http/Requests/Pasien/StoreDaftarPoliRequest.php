<?php

namespace App\Http\Requests\Pasien;

use App\Models\DaftarPoli;
use Illuminate\Foundation\Http\FormRequest;

class StoreDaftarPoliRequest extends FormRequest
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
        $userId = (int) $this->user()->id;

        return [
            'id_jadwal' => [
                'required',
                'exists:jadwal_periksa,id',
                function (string $attribute, mixed $value, \Closure $fail) use ($userId): void {
                    $activeDaftarPoli = DaftarPoli::query()
                        ->where('id_pasien', $userId)
                        ->whereDoesntHave('periksas')
                        ->latest('created_at')
                        ->first();

                    if (! $activeDaftarPoli) {
                        return;
                    }

                    if ((int) $activeDaftarPoli->id_jadwal === (int) $value) {
                        $fail('Anda sudah terdaftar pada jadwal ini dan antrean masih aktif.');

                        return;
                    }

                    if ((int) $activeDaftarPoli->id_jadwal !== (int) $value) {
                        $fail('Anda masih memiliki antrean aktif. Selesaikan pemeriksaan terlebih dahulu sebelum mendaftar lagi.');
                    }
                },
            ],
            'keluhan' => ['required', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'id_jadwal.exists' => 'Jadwal periksa tidak ditemukan.',
        ];
    }
}
