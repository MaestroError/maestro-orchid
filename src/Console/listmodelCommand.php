<?php 
namespace maestroerror\MaestroOrchid\Console;

use Illuminate\Console\Command;
use maestroerror\MaestroOrchid\Facades\mOrchid;

class listmodelCommand extends command {

    protected $signature = "morchid:listmodel {name}";

    protected $description = "Creates New List Model of mOrchid";
    
    public function handle() {

        $modelClassText = file_get_contents(__DIR__ . '/stubs/morchidListModel.stub');
        $name = strtolower($this->argument('name'));
        $newText = str_replace("__NAME__", $name, $modelClassText);
        // make mOrchid folder
        if(!file_exists(app_path("mOrchid/"))) {
            mkdir(app_path("mOrchid/"));
        }
        file_put_contents(app_path("mOrchid/".$name.".php"), $newText);

        if (file_exists(app_path("mOrchid/".$name.".php"))) {
            return $this->info("New List Model Created successfully");
        } else {
            return $this->warn("Model not created, please try again");
        }

    }
}