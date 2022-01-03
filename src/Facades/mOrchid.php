<?php

namespace maestroerror\MaestroOrchid\Facades;

use illuminate\Support\Facades\Facade;

class mOrchid extends Facade {
    protected static function getFacadeAccessor() {
        return "morchid-singleton";
    }
}