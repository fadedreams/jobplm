<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users', function () {
    return view('users.index');
});

Route::get('/register/seeker', [UserController::class, 'createSeeker'])->name('register.seeker');
Route::post('/register/seeker', [UserController::class, 'storeSeeker'])->name('store.seeker');
Route::get('/login', [UserController::class, 'login'])->name('login');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/contact/store', [ContactController::class, 'store'])->name('store');
