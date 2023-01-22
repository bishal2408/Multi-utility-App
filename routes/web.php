<?php

use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Route;

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

Route::get('/qr-generator', [AppController::class, 'QrGenerator'])->name('qr.generator');
Route::get('/nepali-to-english', [AppController::class, 'getEnglishDate'])->name('nepali.to.english.date');
Route::get('/english-to-nepali', [AppController::class, 'getNepaliDate'])->name('english.to.nepali.date');
Route::get('/random-password', [AppController::class, 'getRandomPassword'])->name('random.password');
Route::post('/image-compressor', [AppController::class, 'getCompressedImage'])->name('image.compressor');
Route::post('/image-convert', [AppController::class, 'getConvertedImage'])->name('image.convert');
Route::post('/word-to-pdf', [AppController::class, 'convertWordToPdf'])->name('word.to.pdf');

