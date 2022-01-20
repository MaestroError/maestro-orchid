<?php
namespace maestroerror\MaestroOrchid\Orchid\Layouts;

use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use App\Models\User;

use maestroerror\MaestroOrchid\Facades\morchid;


class modelListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'posts';

    public $extraInfo = [];
    public $model = ''; // referenced model object

    public function __construct($obj, $titleName = "title", $modBy = "") {
        $this->model = $obj;
        $this->nameing = $titleName;
        $this->modBy = $modBy;
        $this->extraInfo = $this->model->extraInfo();
    }

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        
        $table = [
            TD::make('id', "ID")
            ->filter(TD::FILTER_TEXT)
            ->sort(),
            TD::make($this->nameing,  $this->extraInfo['title'])
            ->filter($this->extraInfo['filter_text'])
            ->sort()
                ->render(function ($post) { return $this->model->titleRenderList($post); })
        ];


        if (!empty($this->model->displayList)) {
            foreach ($this->model->displayList as $item){
                if (isset($this->extraInfo[$item])) {
                    $title = $this->extraInfo[$item];
                } else {
                    $title = $item;
                }
                $column = TD::make($item, $title)
                ->filter($this->extraInfo['filter_text'])
                ->sort();
                    
                
                if (method_exists($this->model, $item."RenderList")) {
                    $column->render(function ($post) use ($item) { 
                        if (method_exists($this->model, $item."RenderList")) {
                            return $this->model->{$item."RenderList"}($post); 
                        }
                    });
                }
                    

                $table[] = $column;
            }
        }

        // Check if modBy needed
        if (!empty($this->modBy)) {
            $modified_by = TD::make($this->modBy,  $this->extraInfo['modified_by'])
            ->filter(TD::FILTER_TEXT)
            ->sort()
                ->render(function ($post) { return $this->model->userRenderList($post); });
            $table[] = $modified_by;
        }

        // if registered
        if(in_array("created_at", $this->model->listFields)) {
            $table[] = TD::make('created_at', $this->extraInfo['created_at'])
            ->filter(TD::FILTER_TEXT)
            ->sort();
        }

        if(in_array("updated_at", $this->model->listFields)) {
            $table[] = TD::make('updated_at', $this->extraInfo['updated_at'])
            ->filter(TD::FILTER_TEXT)
            ->sort();
        }
        
        

        return $table;
    }

}