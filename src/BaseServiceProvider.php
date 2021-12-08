<?php

namespace maestroerror\MaestroOrchid;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class BaseServiceProvider extends ServiceProvider {
    public function boot() {

        // publish only if request is from console
        if($this->app->runningInConsole()) {
            $this->registerPublishing();
        }

        $this->registerResources();
    }   

    public function register() {
        $this->commands([
            Console\ProcessCommand::class,
        ]);
    }

    private function registerResources() {
        // $this->loadMigrationsFrom(__DIR__."/../database/migrations");
        $this->loadViewsFrom(__DIR__."/../resources/views", "morchid");

        $this->registerRoutes();
    }


    // publish files
    protected function registerPublishing() {
        // register publishing config
        $this->publishes([
            __DIR__."/../config/morchid.php" => config_path("morchid.php"),
        ], "morchid-config");
    }

    // register routes from routes file
    protected function registerRoutes() {
        
        Route::group($this->routeConfig(), function () {
            $this->loadRoutesFrom(__DIR__."/../routes/web.php");
        });
    }

    private function routeConfig() {
        return [
            'prefix' => morchid::routePrefix(),
        ];
    }
}