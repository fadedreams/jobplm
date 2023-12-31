<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\SubController;
use App\Http\Middleware\IsEmployer;
use App\Http\Controllers\PostJobController;
use App\Http\Middleware\IsPremiumUser;

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

Route::get('/register/employer', [UserController::class, 'createEmployer'])->name('register.employer');
Route::post('/register/employer', [UserController::class, 'storeEmployer'])->name('store.employer');

Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/login', [UserController::class, 'postLogin'])->name('login.post');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('verified')->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');


Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/login');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/verify', [DashboardController::class, 'verify'])->name('verification.notice');
Route::get('/resend/verification/email', [DashboardController::class, 'resend'])->name('resend.email');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/contact/store', [ContactController::class, 'store'])->name('store');

Route::get('subscribe', [SubController::class, 'subscribe'])->middleware(['auth', IsEmployer::class])->name('subscribe');
// Route::get('/subscribe', [SubController::class, 'subscribe'])->name('subscribe');
Route::get('pay/weekly', [SubController::class, 'initiatePayment'])->name('pay.weekly');
Route::get('pay/monthly', [SubController::class, 'initiatePayment'])->name('pay.monthly');
Route::get('pay/yearly', [SubController::class, 'initiatePayment'])->name('pay.yearly');
Route::get('payment/success', [SubController::class, 'paymentSuccess'])->name('payment.success');
Route::get('payment/cancel', [SubController::class, 'cancel'])->name('payment.cancel');

Route::get('job/create', [PostJobController::class, 'create'])->name('job.create')->middleware(['auth', IsPremiumUser::class]);
Route::post('job/store', [PostJobController::class, 'store'])->name('job.store');
Route::get('job/{listing}/edit', [PostJobController::class, 'edit'])->name('job.edit');
Route::put('job/{id}/edit', [PostJobController::class, 'update'])->name('job.update');
Route::get('job', [PostJobController::class, 'index'])->name('job.index');
Route::delete('job/{id}/delete', [PostJobController::class, 'destroy'])->name('job.delete');
