<?php

namespace App\mOrchid; 

use maestroerror\MaestroOrchid\Models\ListModel;
use maestroerror\MaestroOrchid\Facades\mOrchid;

class posts extends ListModel {

    protected $table="posts";
    public $fillable = [
        "title",
        "body",
        "image",
    ];

    protected $mName = "Post";
    protected $mNamePlural = "Posts";
    
    // Fields to calculate
    public $calculatedFields = [];

    // permission to check
    public $orchidPermissions ="platform.index";

    public $displayList = [
        "body"
    ];


    // fields for auto generation
    protected $objectFields = [
        "title" => [
            "title" => "Title",
            "field" => "Input",
            "placeholder" => "Enter Posts's Title",
            "fieldOptions" => [
                "required" => true,
            ]
        ],
        "body" => [
            "title" => "Body",
            "field" => "TextArea",
            "required" => true,
        ],
        "image" => [
            "title" => "Post's Image",
            "field" => "Cropper",
            "fieldOptions" => [
                "height" => 500,
                "width" => 1000,
            ],
        ],
    ];

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
        $this->constructor();
    }
    
}