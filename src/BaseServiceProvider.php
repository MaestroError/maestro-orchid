<?php

namespace maestroerror\MaestroOrchid;

use Illuminate\Support\ServiceProvider;

class BaseServiceProvider extends ServiceProvider {
    public function boot() {
        if($this->app->runningInConsole()) {
            $this->registerPublishing();
        }
    }   

    public function register() {
        $this->commands([
            Console\ProcessCommand::class,
        ]);
    }

    private function registerResources() {
        // $this->loadMigrationsFrom(__DIR__."/../database/migrations");
    }


    protected function registerPublishing() {
        // register publishing config
        $this->publishes([
            __DIR__."/../config/morchid.php" => config_path("morchid.php"),
        ], "morchid-config");
    }
}