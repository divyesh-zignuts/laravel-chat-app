<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
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

Route::get('/chat', function () {
    broadcast(new \App\Events\PublicChannel("message new"));
   echo "OK";
   die;
});

Auth::routes();

Auth::routes(['register' => false]);

Route::get('/forgot', function () { return view('auth.passwords.email'); });
Route::post('reset_password', [App\Auth\ForgotPasswordController::class,'passwordRequest']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::group(['middleware' => ['auth']], function() {
    Route::resource('home', HomeController::class);
    Route::resource('posts',PostController::class);
    Route::get('/getUserChat/{id}', [App\Http\Controllers\MessageController::class, 'getUserChat'])->name('getUserChat');
    Route::get('/getUserDetails/{id}', [App\Http\Controllers\MessageController::class, 'getUserDetails'])->name('getUserDetails');
    Route::post('/sendMessage', [App\Http\Controllers\MessageController::class, 'sendMessage'])->name('sendMessage');
});
