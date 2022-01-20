<?php

namespace maestroerror\MaestroOrchid\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

use App\Http\Traits\baseTrait;


abstract class ListModel extends Model
{
    use AsSource, Attachable, Filterable, HasFactory; 
    use baseTrait;
    /**
     * @var array
     */
    protected $allowedFilters = [];

    /**
     * @var array
     */
    protected $allowedSorts = [];
}
