<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AdminDokterExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDokterRequest;
use App\Http\Requests\Admin\UpdateDokterRequest;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class DokterController extends Controller
{
    public function index()
    {
        $dokters = User::with('poli')
            ->where('role', 'dokter')
            ->latest()
            ->get();

        return view('admin.dokter.index', compact('dokters'));
    }

    public function create()
    {
        $polis = Poli::orderBy('nama_poli')->get();

        return view('admin.dokter.create', compact('polis'));
    }

    public function store(StoreDokterRequest $request)
    {
        $validated = $request->validated();

        User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'alamat' => $validated['alamat'],
            'no_ktp' => $validated['no_ktp'],
            'no_hp' => $validated['no_hp'],
            'id_poli' => $validated['id_poli'],
            'role' => 'dokter',
        ]);

        return redirect()->route('admin.dokter.index')->with('success', 'Dokter berhasil ditambahkan');
    }

    public function edit(string $id)
    {
        $dokter = User::where('role', 'dokter')->findOrFail($id);
        $polis = Poli::orderBy('nama_poli')->get();

        return view('admin.dokter.edit', compact('dokter', 'polis'));
    }

    public function update(UpdateDokterRequest $request, string $id)
    {
        $dokter = User::where('role', 'dokter')->findOrFail($id);
        $validated = $request->validated();

        $payload = [
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'alamat' => $validated['alamat'],
            'no_ktp' => $validated['no_ktp'],
            'no_hp' => $validated['no_hp'],
            'id_poli' => $validated['id_poli'],
            'role' => 'dokter',
        ];

        if (! empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $dokter->update($payload);

        return redirect()->route('admin.dokter.index')->with('success', 'Data dokter berhasil diupdate');
    }

    public function destroy(string $id)
    {
        $dokter = User::where('role', 'dokter')->findOrFail($id);
        $dokter->delete();

        return redirect()->route('admin.dokter.index')->with('success', 'Dokter berhasil dihapus');
    }

    public function export()
    {
        return Excel::download(new AdminDokterExport(), 'data-dokter.xlsx');
    }
}
