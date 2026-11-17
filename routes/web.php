
<?php

use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Halaman daftar Palette
Route::get('/palettes', function () {
    return view('palettes.index');
});
