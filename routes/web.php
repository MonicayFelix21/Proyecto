<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use App\Models\Cancion;
use App\Http\Controllers\CancionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArtistaController;
use App\Http\Controllers\DashboardController;

Route::get('/', [HomeController::class, 'index'])->name('inicio');

Route::get('/artistas', [ArtistaController::class, 'index'])->name('artistas.index');

// Ruta de búsqueda AJAX
Route::get('/buscar-canciones', [CancionController::class, 'buscar'])->name('canciones.buscar');
Route::get('/buscar-cancion', function (Request $request) {
    $id = $request->get('id');
    $cancion = Cancion::find($id);

    if (!$cancion) {
        return response()->json(['error' => 'Canción no encontrada'], 404);
    }

    return response()->json($cancion);
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/registro', function () {
    return view('auth.registro');
})->name('registro');

Route::post('/registro', [RegistroController::class, 'registrar'])->name('registro.submit');

Route::get('/verificar-email', function (Request $request) {
    $existe = DB::table('users')
                ->where('email', $request->email)
                ->exists();
    return response()->json(['existe' => $existe]);
});  // ← Aquí cerramos correctamente el callback :contentReference[oaicite:0]{index=0}

Route::get('/dashboard', [DashboardController::class, 'index'])
     ->name('dashboard')
     ->middleware('auth');
