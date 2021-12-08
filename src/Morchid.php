<?php

namespace maestroerror\MaestroOrchid;

class Morchid {

    public static function configNotPublished() {
        return is_null(config("morchid"));
    }

}