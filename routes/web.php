<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\TopTenController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/quiz', [QuizController::class, 'index'])->name('quiz.index');
Route::post('/quiz/play', [QuizController::class, 'play'])->name('quiz.play');
Route::post('/quiz/finish', [QuizController::class, 'finish'])->name('quiz.finish');

Route::get('/top-ten', [TopTenController::class, 'index'])->name('top-ten.index');