<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AdminObatExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreObatRequest;
use App\Http\Requests\Admin\UpdateObatRequest;
use App\Models\Obat;
use Maatwebsite\Excel\Facades\Excel;

class ObatController extends Controller
{
    public function index()
    {
        $obats = Obat::latest()->get();

        return view('admin.obat.index', compact('obats'));
    }

    public function create()
    {
        return view('admin.obat.create');
    }

    public function store(StoreObatRequest $request)
    {
        Obat::create($request->validated());

        return redirect()->route('admin.obat.index')->with('success', 'Obat berhasil ditambahkan');
    }

    public function edit(string $id)
    {
        $obat = Obat::findOrFail($id);

        return view('admin.obat.edit', compact('obat'));
    }

    public function update(UpdateObatRequest $request, string $id)
    {
        $obat = Obat::findOrFail($id);
        $obat->update($request->validated());

        return redirect()->route('admin.obat.index')->with('success', 'Obat berhasil diupdate');
    }

    public function destroy(string $id)
    {
        $obat = Obat::findOrFail($id);
        $obat->delete();

        return redirect()->route('admin.obat.index')->with('success', 'Obat berhasil dihapus');
    }

    public function export()
    {
        return Excel::download(new AdminObatExport, 'data-obat.xlsx');
    }
}
