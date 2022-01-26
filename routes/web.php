<?php

use maestroerror\MaestroOrchid\Facades\morchid;
use maestroerror\MaestroOrchid\Orchid\Screens\modelListScreen;
use maestroerror\MaestroOrchid\Orchid\Screens\modelEditScreen;

Route::group(['prefix' => morchid::routePrefix(), 'middleware' => 'web'], function () {
    // List route
    Route::screen('list/{model}', modelListScreen::class)
        ->name('morchid::list');
    // Edit route
    Route::screen('edit/{model}/{id?}', modelEditScreen::class)
        ->name('morchid::edit');
});
