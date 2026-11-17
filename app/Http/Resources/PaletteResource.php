<?php

namespace App\Http\Resources;

use App\Http\Resources\PaletteResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaletteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_palette' => $this->nama_palette,
            'kategori' => $this->kategori,
            'array_warna' => $this->array_warna,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

