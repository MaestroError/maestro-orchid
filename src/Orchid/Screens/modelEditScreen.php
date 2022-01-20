<?php
namespace maestroerror\MaestroOrchid\Orchid\Screens;

// Fields
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Fields\Code;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\TimeZone;
use Orchid\Screen\Fields\SimpleMDE;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Picture;

use App\Models\User;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use maestroerror\MaestroOrchid\Facades\morchid;

class modelEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = '';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = '';

    /**
     * @var bool
     */
    public $exists = false;

    public $extraInfo = [];
    public $model = ''; // referenced model object

    public function getBuilded($model, $post) {
        // get namespace from config file
        $modelName = morchid::ModelsNamespace().$model;
        $model = new $modelName;

        $this->model = $model;
        $this->extraInfo = $model->extraInfo();
        
        $obj = $this->extraInfo['obj'];
        
        // Many To Many relationships
        $this->mtmRelations = [];
        if(!empty($model->mtmRelations)) {
            foreach($model->mtmRelations as $rel) {
                $this->mtmRelations[$rel] = "$obj.$rel";
            }
        }
        
        if($post) {
            $post = $model::find($post);

        } else {
            $post = $model;
        }

        return $post;
    }

    /**
     * Query data.
     *
     * @param  $post
     *
     * @return array
     */
    public function query($model, $post): array
    {
        $post = $this->getBuilded($model, $post);

        return $post->editScreenQuery($this, $post);

    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        $updateBtn = "";
    
        $btns = [
            Button::make($this->extraInfo['add'])
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

            Button::make($this->extraInfo['delete'])
                ->icon('trash')
                ->method('remove')
                ->confirm("ნამდვილდ გსურთ ჩანაწერის წაშლა?")
                ->class('btn-danger btn')
                ->canSee($this->exists),
        ];
        if (!empty($this->model->objectFields)){
            $updateBtn = Button::make($this->extraInfo['update'])
            ->icon('note')
            ->method('createOrUpdate')
            ->canSee($this->exists);
            $btns[] = $updateBtn;
        }

        return $btns;
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {  
        // field insertion example
        $obj = $this->extraInfo['obj'];
        $fields = ['erti' => "1", 'ori' => "2", 'sami' => "3"];
        $fieldInserted = $this->model->insertNewField("ori", $fields, "inserted", "2.5");
        //print_r($fieldInserted);
        $inputs =  $this->model->getMyForm($obj);
        
        return [
            Layout::rows(
                $inputs
            )
        ];
    }

    /**
     * @param     $post
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate($model, $post, Request $request)
    {
        
        $post = $this->getBuilded($model, $post);
        
        return $post::editScreenCreateOrUpdate($this, $post, $request, $this->mtmRelations, $post->authorName);
    }

    /**
     * @param  $post
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove($model, $post)
    {
        $post = $this->getBuilded($model, $post);
        return $post::editScreenRemove($this, $post, $this->mtmRelations);
    }
}