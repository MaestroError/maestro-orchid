<?php
namespace maestroerror\MaestroOrchid\Orchid\Screens;

use maestroerror\MaestroOrchid\Orchid\Layouts\modelListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

use maestroerror\MaestroOrchid\Facades\morchid;

class modelListScreen extends Screen
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

    public $extraInfo = [];
    public $model = ''; // referenced model object

    public function getBuilded($model) {
        // get namespace from config file
        $modelName = morchid::ModelsNamespace().$model;
        $model = new $modelName;
        $this->model = $model;
        $this->extraInfo = $model->extraInfo();
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query($model): array
    {
        $this->getBuilded($model);
        return $this->model->listScreenQuery($this, $this->model);
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        $obj = $this->extraInfo['obj'];
        return [
            Link::make($this->extraInfo['create_new'])
                ->icon('pencil')
                ->route("morchid::edit", ["model" => $obj])
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            new modelListLayout($this->model, $this->model->titleName, $this->model->authorName)
        ];
    }
}