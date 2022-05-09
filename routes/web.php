<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VerificationController;
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
    return view('welcome');
});

    /**
    * Verification Routes
    */
    Route::get('/email/verify', [VerificationController::class,'show'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}',[VerificationController::class,'verify'])->name('verification.verify')->middleware(['signed']);
    Route::post('/email/resend',[VerificationController::class,'resend'] )->name('verification.resend');

Auth::routes([ 'register' => false, 
  'reset' => false, 
  'verify' => true,
  'login'=>true,
    ]);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
