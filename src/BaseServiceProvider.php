<?php

namespace maestroerror\MaestroOrchid;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

use maestroerror\MaestroOrchid\Facades\mOrchid;

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
            Console\listmodelCommand::class,
        ]);
    }

    private function registerResources() {
        // $this->loadMigrationsFrom(__DIR__."/../database/migrations");
        $this->loadViewsFrom(__DIR__."/../resources/views", "morchid");

        $this->registerFacades();
        $this->registerRoutes();
    }


    // publish files
    protected function registerPublishing() {
        // register publishing config
        $this->publishes([
            __DIR__."/../config/morchid.php" => config_path("morchid.php"),
        ], "morchid-config");
        // publishes public service provider
        $this->publishes([
            __DIR__."/Console/stubs/morchidServiceProvider.stub" => app_path("Providers/morchidServiceProvider.php"),
        ], "morchid-provider");
        // publishes example model
        $this->publishes([
            __DIR__."/../app/mOrchid/posts.php" => app_path("mOrchid/posts.php"),
        ], "morchid-model");
    }

    // register routes from routes file
    protected function registerRoutes() {
        
        Route::group($this->routeConfig(), function () {
            $this->loadRoutesFrom(__DIR__."/../routes/web.php");
        });
    }

    private function routeConfig() {
        return [
            'prefix' => mOrchid::routePrefix()
        ];
    }

    protected function registerFacades() {
        $this->app->singleton('morchid_singleton', function ($app) {
            return new \maestroerror\MaestroOrchid\mOrchid();
        });
    }
}