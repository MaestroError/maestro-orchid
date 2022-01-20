<?php

namespace maestroerror\MaestroOrchid\Traits;

use Illuminate\Support\Facades\Auth;

trait baseTrait {
    // Shared variables
    public $availableLangs = [
        "ge" => "ქართული",
        "en" => "ინგლისური"
    ];

    /**
     * @var array
     */
    // protected $allowedFilters = [];

    /**
     * @var array
     */
    // protected $allowedSorts = [];

    // Fields to calculate
    public $calculatedFields = [];

    // permission to check
    public $orchidPermissions ="platform.index";

    // author or modified by column name
    public $authorName = "";

    // Title column name
    public $titleName = "title";
    
    // Title column name
    public $mainID = "id";
    // Title column name
    public $createdDateName = "created_at";
    // Title column name
    public $updatedDateName = "updated_at";

    public $displayList = [
        // "fieldName1",
        // "fieldName2",
        // "..."
    ];

    public $filterToApply = "";

    public $listFields = [];

    /** SCREEN -> LAYOUT -> Fields */

    // namespace of field classes
    protected $fieldsNamespace = "Orchid\Screen\Fields\\";


    // field options, which key is a function and value is argument for that function
    protected $valueFieldOptions = [
        "title",
        "placeholder",
        "help",
        "popover",
        "fromModel",
        "type",
        "mask",
        "rows",
        "value",
        "options",
        "empty",
        "format",
        "toolbar",
        "columns",
        "maxRows",
        "language",
        "width",
        "height",
        "minCanvas",
        "maxWidth",
        "maxHeight",
        "maxFileSize",
        "maxFiles",
        "canSee",
        "acceptedFiles",
        "displayAppend"
    ];
    
    // field options, which key is a function and value boolean to decide use function or not (true/false)
    protected $boolFieldOptions = [
        "required",
        "sendTrueOrFalse",
        "allowInput",
        "format24hr",
        "enableTime",
        "noCalendar",
        "lineNumbers",
        "targetId",
        "targetRelativeUrl",
        "targetUrl",
        "multiple",
    ];

    // fields object example
    protected $objectFields = [
        'fieldName' => [
            "title" => "title of field",
            "field" => "field class name ex: Input",
            "placeholder" => "placeholder",
            "help" => "helping text",
            "popover" => "Tooltip - hint that user opens himself.",
            "required" => false,
            "canSee" => true, // hiding
            "fieldOptions" => [
                "fromModel" => [ // if field = Select or Relation
                    "class" => ClassName::class,
                    "field" => "field name"
                ],
                "type" => 'text', // if field = Input
                "mask" => ['mask' => '999 999 999.99','numericInput' => true], // if field = Input
                "rows" => 3, // if field = TextArea
                "value" => 1, // if field = CheckBox
                "sendTrueOrFalse" => false, // if field = CheckBox
                "options" => ["value" => 'title'], //  if field = Select
                "empty" => "empty selec value", //  if field = Select
                "allowInput" => true, // if field = DateTimer
                "format" => "Y-m-d", // if field = DateTimer
                "format24hr" => true, // if field = DateTimer
                "enableTime" => false, // if field = DateTimer
                "noCalendar" => false, // if field = DateTimer
                "toolbar" => ["text", "color", "header", "list", "format", "media"], // if field = Quill
                "columns" => ['id', 'name'], //  if field = Matrix
                "maxRows" => 10, // if field = Matrix
                "language" => \Orchid\Screen\Fields\Code::CSS, // if field = Code
                "lineNumbers" => true, // if field = Code
                "width" => 500, // if field = Cropper
                "height" => 500, // if field = Cropper
                "minCanvas" => 500, // if field = Cropper
                "maxWidth" => 500, // if field = Cropper
                "maxHeight" => 500, // if field = Cropper
                "maxFileSize" => 2, // if field = Cropper (in MB)
                "targetId" => false, // if field = Cropper 
                "targetRelativeUrl" => false, // if field = Cropper 
                "targetUrl" => false, // if field = Cropper ,
                "multiple" => false, // if field = Relation or Select
                "maxFiles" => 10, // if field = Upload
                "acceptedFiles" => "image/*",  // if field = Upload,
                "displayAppend" => "dynamicAtribute"
            ]
        ],
    ];


    public function constructor() {
        // dynamic value (languages defined in parent model as property)
        if (isset($this->objectFields['lang']['fieldOptions']['options'])) {
            $this->objectFields['lang']['fieldOptions']['options'] = $this->{$this->objectFields['lang']['fieldOptions']['options']};
        }

        $listFields = [];
        
                
        if (!empty($this->mainID)) {
            $this->allowedFilters[] = $this->mainID;
            $this->allowedSorts[] = $this->mainID;
            $listFields[] = $this->mainID;
        }
                
        if (!empty($this->titleName)) {
            $this->allowedFilters[] = $this->titleName;
            $this->allowedSorts[] = $this->titleName;
            $listFields[] = $this->titleName;
        }

        if (!empty($this->displayList)) {
            foreach ($this->displayList as $item){
                $this->allowedFilters[] = $item;
                $this->allowedSorts[] = $item;
                $listFields[] = $item;
            }
        }

        if (!empty($this->authorName)) {
            $this->allowedFilters[] = $this->authorName;
            $this->allowedSorts[] = $this->authorName;
            $listFields[] = $this->authorName;
        }
                
        if (!empty($this->createdDateName)) {
            $this->allowedFilters[] = $this->createdDateName;
            $this->allowedSorts[] = $this->createdDateName;
            $listFields[] = $this->createdDateName;
        }
                
        if (!empty($this->updatedDateName)) {
            $this->allowedFilters[] = $this->updatedDateName;
            $this->allowedSorts[] = $this->updatedDateName;
            $listFields[] = $this->updatedDateName;
        }

        $this->listFields = $listFields;

    }

    // generates form from objectFields property
    public function getMyForm($objectName) {
        $formArray = [];
        foreach ($this->objectFields as $fieldName => $field) {
            // find right field
            $fieldType = $this->fieldsNamespace.$field['field'];
            $obj = new $fieldType();
            // init
            $fieldObj = $obj::make("$objectName.$fieldName");
            // Pass through options
            $fieldObj = $this->passTroughOptions($field, $fieldObj);

            // add in form
            $formArray[] = $fieldObj;
        }
        return $formArray;
    }

    // Check and Apply all options
    protected function passTroughOptions($field, $object) {
        foreach ($field as $function => $value) {
            $object = $this->applyOption($function, $value, $object);
        }
        if (isset($field['fieldOptions'])) {
            foreach ($field['fieldOptions'] as $function => $value) {
                $object = $this->applyOption($function, $value, $object);
            }
        }
        return $object;
    }

    // use option as function at field class
    protected function applyOption($function, $value, $object) {
        if (in_array($function, $this->valueFieldOptions)) {
            if ($function == "fromModel") {
                $object->$function($value['class'], $value['field']);
            } else {
                $object->$function($value);
            }
        } elseif (in_array($function, $this->boolFieldOptions)) {
            $object->$function();
        }
        return $object;
    }

    // manualy inserts new field in $fields after $afterField
    public function insertNewField($afterField, $fields, $newFieldName, $newField) {
        $i = array_search($afterField, array_keys($fields));
        $res = array_slice($fields, 0, $i+1, true) +
        array("$newFieldName" => $newField) +
        array_slice($fields, $i, count($fields) - 1, true);
        return $res;
    }

    // removes field from fields by it's name
    public function deleteField($fields, $fieldName) {
        unset($fields[$fieldName]);
        return $fields;
    }
    
    /** SCREEN -> COMMON */

    // main info for creating screen, can be overridden by child class
    public function extraInfo() {
        $reflect = new \ReflectionClass($this);
        $extraInfo = [
            "name" => $this->geoName,
            "plural_name" => $this->geoNamePlural,
            "obj" => $reflect->getShortName(),
            "add" => "დამატება",
            "update" => "განახლება",
            "delete" => "წაშლა",
            "create_new" => "დამატება",
            "edit" => "რედაქტირება",
            "top_news" => "top სიახლე",
            "up_to_slide" => "სლაიდზე დამატება",
            "category" => "კატეგორია",
            "up_to_euro" => "ეუროზე დამატება",
            "title" => "დასახელება",
            "modified_by" => "შეცვალა",
            "author" => "ავტორი",
            "created" => "შეიქმნა:",
            "created_at" => "შეიქმნა",
            "updated_at" => "განახლდა",
            "personal_id" => "პირადი ნომერი",
            "croco_id" => "კროკოს აიდი",
            "first_name" => "სახელი",
            "last_name" => "გვარი",
            "email" => "მეილი",
            "favorite_team" => "საყვარელი გუნდი",
            "date_of_birth" => "დაბადების თარიღი",
            "gender" => "სქესი",
            "filter_text" => \Orchid\Screen\TD::FILTER_TEXT,
        ];
        $this->extraInfo = $extraInfo;
        return $extraInfo;
    }

    /** SCREEN -> QUERY */

    // Query function
    // $defs array: key = $post's property, value = property's default value while create
    // $fixs array: key = $post's property, value = property's fixed value while update
    public function editScreenQuery($screen, $post, $defs = [], $fixs = []) {
        // Checking Orchid Permissions
        if(!Auth::user()->hasAccess($this->orchidPermissions)) {
            Throw new \Exception('No permission for model '.get_class($this));
        }

        $screen->exists = $post->exists;

        if($screen->exists){
            $screen->name = 'შეცვალე '.$screen->extraInfo['name'];

            // Fixed Values while updating
            // $post->lang = "en";
            if (is_array($fixs) && !empty($fixs)) {
                foreach ($fixs as $property => $value) {
                    $post->{$property} = $value;
                }
            }
        } else {
            $screen->name = 'დაამატე '.$screen->extraInfo['name'];

            // Default Values while creating
            //$post->identificator = "1234";
            if (is_array($defs) && !empty($defs)) {
                foreach ($defs as $property => $value) {
                    $post->{$property} = $value;
                }
            }
        }

        // gets calculated fields
        if(!empty($post->calculatedFields)) {
            foreach($post->calculatedFields as $field => $method) {
                $post->{$field} = $post->{$method['retrive']}();
            }
        }


        $obj = $screen->extraInfo['obj'];
        return [
            "$obj" => $post
        ];
    }

    static function editScreenCreateOrUpdate($screen, $post, $request, $mtmRelations = [], $setAuthor="", $alertObj = "\Orchid\Support\Facades\Alert") {
        
        if (!$post->identificator) {
            if (in_array("identificator", $post->fillable)) {
                $post->identificator = $post->generateIdentificator();
            }
        }

        
        $obj = $screen->extraInfo['obj'];
        $reqData = $request->get($obj);
        
        // if(isset($reqData['desc'])) {
        //     $reqData['desc'] = htmlspecialchars_decode($reqData['desc']);
        //     // dd($reqData);
        // }

        if(isset($reqData['video'])) {
            $videoUrl = $reqData['video'];
            $linuxString = "cdn01.croco.ge/www/wwwroot/cdn01.croco.ge/content/";
            if(str_contains($videoUrl, $linuxString)) {
                $videoUrl = str_replace($linuxString, "cdn01.croco.ge/content/", $videoUrl);
            }
            if(!str_contains($videoUrl, "https://")) {
                $videoUrl = str_replace("http://", "https://", $videoUrl);
            }
            $reqData['video'] = $videoUrl;
        }

        if(isset($reqData['background_url'])) {
            $videoUrl = $reqData['background_url'];
            $linuxString = "cdn01.croco.ge/www/wwwroot/cdn01.croco.ge/content/";
            if(str_contains($videoUrl, $linuxString)) {
                $videoUrl = str_replace($linuxString, "cdn01.croco.ge/content/", $videoUrl);
            }
            if(!str_contains($videoUrl, "https://")) {
                $videoUrl = str_replace("http://", "https://", $videoUrl);
            }
            $reqData['background_url'] = $videoUrl;
        }

        // dd($reqData);
        // $post->fill($reqData)->save();
        if(!isset($post->id)){
            $post = $post::create([]);
        }

        // gets calculated fields
        if(!empty($post->calculatedFields)) {
            foreach($post->calculatedFields as $field => $method) {
                $request->{$field} = $post->{$method['save']}($reqData[$field]);
                $reqData[$field] = $post->{$method['save']}($reqData[$field]);
            }
        }
        

        // sets author relation name
        if(!empty($setAuthor)) {
            if(!isset($post->{$setAuthor}) || empty($post->{$setAuthor})) {
                $post->{$setAuthor} = Auth::user()->id;
            }
        }
        // dd($post);
        $post->fill($reqData)->save();

        $alert = new $alertObj;
        $alert::info('თქვენ წარმატებით შექმენით '.$screen->extraInfo['name'].'.');

        if (!empty($mtmRelations)) {
            foreach ($mtmRelations as $relName => $relVal) {
                if ($request->input($relVal)) {
                    $info = $request->input($relVal);
                } else {
                    $info = [];
                }
                $post->{$relName}()->sync($info);
            }
        }
        

        return redirect()->route("platform.list", ["model" => $obj]);
    }

    static function editScreenRemove($screen, $post, $mtmRelations = [], $alertObj = "\Orchid\Support\Facades\Alert") {

        if (!empty($mtmRelations)) {
            foreach ($mtmRelations as $relName => $relVal) {
                $post->{$relName}()->sync([]);
            }
        }

        $post->delete();
        $obj = $screen->extraInfo['obj'];

        $alert = new $alertObj;
        $alert::info('თქვენ წარმატებით წაშალეთ '.$screen->extraInfo['name'].'');

        return redirect()->route("platform.list", ["model" => $obj]);
    }

    public function listScreenQuery($screen, $post) {
        // Checking Orchid Permissions
        if(!Auth::user()->hasAccess($this->orchidPermissions)) {
            Throw new \Exception('No permission for model '.get_class($this));
        }

        // getting screen name and description
        $screen->name = $screen->extraInfo['plural_name'];
        $screen->description = $screen->extraInfo['plural_name']."ს მენეჯმენტის სექცია";

        $filtersArray = [];
        // filter selection romelic unda gamoviyenot query-ze ex.: $post::filtersApplySelection(RoleSelection::class)
        if(!empty($post->filterToApply)) {
            $filtersArray = $post->filterToApply;
        }
        
        // returning
        return [
            'posts' => $post::filters()->defaultSort('id', "DESC")->paginate(60)
        ];
    }

    // TD Call to undefined method App\Models\category::getContent() (View: C:\Users\Revaz\Desktop\EO\eo-live\vendor\orchid\platform\resources\views\layouts\table.blade.php) FIX
    // public function getContent($name) {
    //     return $this->{$name};
    // }

    public function generateIdentificator() {
        $length = 12;
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function userRenderList($post) {
        $this->extraInfo();
        $obj = $post;
        $id = $obj->{$this->authorName};
        $user = \App\Models\User::find($id);
        if ($user) {
            return \Orchid\Screen\Actions\Link::make($user->name)
                ->route("platform.systems.users.edit", $user);
        } else {
            return $id;
        }
    }

    public function titleRenderList($post) {
        $this->extraInfo();
        $obj = $this->extraInfo['obj'];
        if (!empty($post->{$this->titleName})) {
            $name = $post->{$this->titleName};
        } else {
            $name = $this->extraInfo['edit'];
        }
        return \Orchid\Screen\Actions\Link::make($name)
            ->route("platform.edit", ["model" => $obj, "id"=>$post->id]);
    }
    public function getFills() {
        return $this->fillable;
    }

    public function getTable() {
        return $this->table;
    }
}