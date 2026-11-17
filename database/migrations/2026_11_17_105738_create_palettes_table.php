
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('palettes', function (Blueprint $table) {
            $table->id();
            $table->string('nama_palette');
            $table->string('kategori');
            $table->json('array_warna');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('palettes');
    }
};
