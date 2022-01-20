<?php

use maestroerror\MaestroOrchid\Facades\morchid;

Route::group(['prefix' => morchid::routePrefix()], function () {
    // List route
    Route::screen('list/{model}', modelListScreen::class)
        ->name('morchid::platform.list');
    // Edit route
    Route::screen('edit/{model}/{id?}', modelEditScreen::class)
        ->name('morchid::platform.edit');
});
