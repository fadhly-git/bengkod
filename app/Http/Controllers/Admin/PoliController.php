<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePoliRequest;
use App\Http\Requests\Admin\UpdatePoliRequest;
use App\Models\Poli;

class PoliController extends Controller
{
    public function index()
    {
        $polis = Poli::all();
        return view('admin.polis.index', compact('polis'));
    }

    public function create()
    {
        return view('admin.polis.create');
    }

    public function store(StorePoliRequest $request)
    {
        $validated = $request->validated();

        Poli::create($validated);

        return redirect()
            ->route('admin.polis.index')
            ->with('success', 'Poli berhasil ditambahkan')
            ->with('type', 'success');
    }

    public function edit($id)
    {
        $poli = Poli::findOrFail($id);
        return view('admin.polis.edit', compact('poli'));
    }

    public function update(UpdatePoliRequest $request, $id)
    {
        $validated = $request->validated();

        $poli = Poli::findOrFail($id);
        $poli->update($validated);

        return redirect()->route('admin.polis.index')->with('success', 'Poli berhasil diupdate');
    }

    public function destroy($id)
    {
        $poli = Poli::findOrFail($id);
        $poli->delete();

        return redirect()->route('admin.polis.index')->with('success', 'Poli berhasil dihapus');
    }
}
