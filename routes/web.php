<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/users', [UsersController::class, 'index'])->name('users.index');

// Limpieza y re-creación de caches.
Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');
    return 'Cache cleared and regenerated successfully!';
});
