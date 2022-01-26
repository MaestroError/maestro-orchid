<?php 
namespace maestroerror\MaestroOrchid\Console;

use Illuminate\Console\Command;
use maestroerror\MaestroOrchid\Facades\mOrchid;

class usermodelCommand extends command {

    protected $signature = "morchid:usermodel {name}";

    protected $description = "Creates New User Model of mOrchid";
    
    public function handle() {

        $modelClassText = file_get_contents(__DIR__ . '/stubs/morchidUserModel.stub');
        $name = strtolower($this->argument('name'));
        $newText = str_replace("__NAME__", $name, $modelClassText);
        file_put_contents(app_path("mOrchid/".$name.".php"), $newText);

        if (file_exists(app_path("mOrchid/".$name.".php"))) {
            return $this->info("New User Model Created successfully");
        } else {
            return $this->warn("Model not created, please try again");
        }

    }
}