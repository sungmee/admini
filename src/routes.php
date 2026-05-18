<?php

use Illuminate\Support\Facades\Route;
use Sungmee\Admini\Controllers\AuthController;
use Sungmee\Admini\Controllers\PostController;
use Sungmee\Admini\Controllers\TagController;

Route::prefix('admini')->name('admini.')->middleware('web')->group(function () {
    Route::name('auth.')->group(function () {
        Route::get('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/login', [AuthController::class, 'auth'])->name('auth');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });

    Route::resource('tags', TagController::class)->except(['show','create']);

    Route::post('/upload', [PostController::class, 'upload'])->name('upload');

    Route::name('posts.')->group(function () {
        Route::get('/{type}', [PostController::class, 'index'])->name('index');
        Route::get('/{type}/create', [PostController::class, 'create'])->name('create');
        Route::post('/{type}', [PostController::class, 'store'])->name('store');
        Route::get('/{type}/{id}/edit', [PostController::class, 'edit'])->name('edit');
        Route::match(['put','patch'], '/{type}/{id}', [PostController::class, 'update'])->name('update');
        Route::delete('/{type}/{id}', [PostController::class, 'destory'])->name('destory');
    });
});
