<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaletteController;

// API CRUD Palette
Route::apiResource('palettes', PaletteController::class);

