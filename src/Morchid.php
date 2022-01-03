<?php

namespace maestroerror\MaestroOrchid;

class mOrchid {

    public static function configNotPublished() {
        return is_null(config("morchid"));
    }

    public static function routePrefix() {
        return config("morchid.prefix", "morchid");
    }

}