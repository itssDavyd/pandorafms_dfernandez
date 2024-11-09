<?php

use App\Http\Controllers\CitasController;
use App\Http\Controllers\UsersController;
use App\Mail\CitaConfirmadaMail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Si el usuario está autenticado, redirige a home
    if (Auth::check()) {
        return redirect()->route('home');
    }

    // Si no está autenticado, redirige al login
    return redirect()->route('login');
});

//Rutas de autentificación (Laravel UI)
Auth::routes();

//HOME
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// USERS
Route::get('/users', [UsersController::class, 'index'])->name('users.index');
Route::get('/users/determinate-tipo', [UsersController::class, 'determinate_tipo'])->name('users.determinate_tipo');
Route::post('/users/save', [UsersController::class, 'save'])->name('users.save');

// CITAS
Route::get('/citas', [CitasController::class, 'index'])->name('citas.index');
Route::get('/citas/check-date', [CitasController::class, 'check_date'])->name('citas.check_date');

// Ejercicio 1 decodificación de CSV por base de dígitos
Route::get('/decodificacion', [\App\Http\Controllers\DecodificacionController::class, 'index'])->name('decodificacion.index');

// Limpieza y re-creación de caches.
Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');
    return 'Cache cleared and regenerated successfully!';
});
