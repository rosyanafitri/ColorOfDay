<?php

namespace App\Http\Controllers;

use App\Models\Palette;
use App\Http\Resources\PaletteResource;
use Illuminate\Http\Request;

class PaletteController extends Controller
{
    // Mengambil semua data palette
    public function index()
    {
        return PaletteResource::collection(Palette::all());
    }

    // Mengambil satu data palette
    public function show(Palette $palette)
    {
        return new PaletteResource($palette);
    }

    // Menambahkan palette baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_palette' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'array_warna' => 'required|array',
        ]);

        $palette = Palette::create($validated);

        return new PaletteResource($palette);
    }

    // Mengubah data palette
    public function update(Request $request, Palette $palette)
    {
        $validated = $request->validate([
            'nama_palette' => 'sometimes|string|max:255',
            'kategori' => 'sometimes|string|max:255',
            'array_warna' => 'sometimes|array',
        ]);

        $palette->update($validated);

        return new PaletteResource($palette);
    }

    // Menghapus palette
    public function destroy(Palette $palette)
    {
        $palette->delete();

        return response()->json([
            'message' => 'Palette berhasil dihapus'
        ]);
    }
}
