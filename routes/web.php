<?php

use maestroerror\MaestroOrchid\Http\Controllers\testController;

Route::view("test", "morchid::test");

Route::get("controller", [testController::class, "index"]);