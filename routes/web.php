<?php
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Models\Cancion;
use App\Http\Controllers\CancionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RegistroController;



Route::get('/inicio', function () {
    $canciones = Cancion::all(); // sin groupBy
    return view('inicio', compact('canciones'));
});

// Redirección desde "/" a "/inicio"
Route::get('/', function () {
    return redirect('/inicio');
});

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

