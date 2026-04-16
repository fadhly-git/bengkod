<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AdminPasienExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

class PasienController extends Controller
{
    public function index()
    {
        $pasiens = User::where('role', 'pasien')
            ->latest()
            ->get();

        return view('admin.pasien.index', compact('pasiens'));
    }

    public function show(string $id)
    {
        $pasien = User::where('role', 'pasien')->findOrFail($id);

        return view('admin.pasien.show', compact('pasien'));
    }

    public function export()
    {
        return Excel::download(new AdminPasienExport(), 'data-pasien.xlsx');
    }
}
