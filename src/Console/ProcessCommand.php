<?php 
namespace maestroerror\MaestroOrchid\Console;

use Illuminate\Console\Command;
use maestroerror\MaestroOrchid\Facades\mOrchid;

class ProcessCommand extends command {

    protected $signature = "morchid:process";

    protected $description = "";
    
    public function handle() {
        $this->info("Hello");

        // check if config file is published
        if(mOrchid::configNotPublished()) {
            return $this->warn("Please publish maestro-orchid config file 'php artisan vendor:publish --tag=morchid-config'");
        }

    }
}