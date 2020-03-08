<?php

Route::prefix('admini')->name('admini.')->middleware('web')->group(function () {
    Route::name('auth.')->group(function () {
        Route::get('/login', '\Sungmee\Admini\AuthController@login')->name('login');
        Route::post('/login', '\Sungmee\Admini\AuthController@auth')->name('auth');
        Route::post('/logout', '\Sungmee\Admini\AuthController@logout')->name('logout');
    });

    Route::post('/upload', '\Sungmee\Admini\PostController@upload')->name('upload');

    Route::name('posts.')->group(function () {
        Route::get('/{type}', '\Sungmee\Admini\PostController@index')->name('index');
        Route::get('/{type}/create', '\Sungmee\Admini\PostController@create')->name('create');
        Route::post('/{type}', '\Sungmee\Admini\PostController@store')->name('store');
        Route::get('/{type}/{post}/edit', '\Sungmee\Admini\PostController@edit')->name('edit');
        Route::match(['put','patch'], '/{type}/{post}', '\Sungmee\Admini\PostController@update')->name('update');
        Route::delete('/{type}/{post}', '\Sungmee\Admini\PostController@destory')->name('destory');
    });
});