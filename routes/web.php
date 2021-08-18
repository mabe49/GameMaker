<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::resource('/player', 'App\Http\Controllers\PlayerController')
    ->names([
        'index' => 'player.index',
        'create' => 'player.create',
        'edit' => 'player.edit',
        'update' => 'player.update',
        'destroy' => 'player.destroy',
        'store' => 'player.store'
    ])
    ->middleware(['auth']);

Route::resource('/game', 'App\Http\Controllers\GameController')
    ->names([
        'index' => 'game.index',
        'create' => 'game.create',
        'edit' => 'game.edit',
        'update' => 'game.update',
        'destroy' => 'game.destroy',
        'store' => 'game.store'
    ])
    ->middleware(['auth']);
