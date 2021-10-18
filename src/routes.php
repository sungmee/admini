<?php

Route::prefix('admini')->name('admini.')->middleware('web')->group(function () {
    Route::name('auth.')->group(function () {
        Route::get('/login', '\Sungmee\Admini\Controllers\AuthController@login')->name('login');
        Route::post('/login', '\Sungmee\Admini\Controllers\AuthController@auth')->name('auth');
        Route::post('/logout', '\Sungmee\Admini\Controllers\AuthController@logout')->name('logout');
    });

    Route::resource('tags', '\Sungmee\Admini\Controllers\TagController')->except(['show','create']);

    Route::post('/upload', '\Sungmee\Admini\Controllers\PostController@upload')->name('upload');

    Route::name('posts.')->group(function () {
        Route::get('/{type}', '\Sungmee\Admini\Controllers\PostController@index')->name('index');
        Route::get('/{type}/create', '\Sungmee\Admini\Controllers\PostController@create')->name('create');
        Route::post('/{type}', '\Sungmee\Admini\Controllers\PostController@store')->name('store');
        Route::get('/{type}/{id}/edit', '\Sungmee\Admini\Controllers\PostController@edit')->name('edit');
        Route::match(['put','patch'], '/{type}/{id}', '\Sungmee\Admini\Controllers\PostController@update')->name('update');
        Route::delete('/{type}/{id}', '\Sungmee\Admini\Controllers\PostController@destory')->name('destory');
    });
});