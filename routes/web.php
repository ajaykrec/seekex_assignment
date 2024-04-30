<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BucketController;
use App\Http\Controllers\BallController;

Route::get('/', [HomeController::class,'home'])->name('home');
Route::post('/add-suggestion', [HomeController::class,'add_suggestion'])->name('post.suggestion');
Route::resource('/bucket', BucketController::class)->names(getResourceRoutesName('bucket'));
Route::resource('/ball', BallController::class)->names(getResourceRoutesName('ball'));


