<?php

namespace maestroerror\MaestroOrchid;

class mOrchid {

    public function configNotPublished() {
        return is_null(config("morchid"));
    }

    public function routePrefix() {
        return config("morchid.prefix", "morchid");
    }

    public function ModelsNamespace() {
        return config("morchid.models_namespace", "App\\mOrchid\\");
    }

    public function FieldsNamespace() {
        return config("morchid.fields_namespace", "Orchid\\Screen\\Fields\\");
    }

    public function fields() {
        // register fields
    }

}