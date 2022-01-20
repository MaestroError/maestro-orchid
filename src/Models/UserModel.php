<?php

namespace maestroerror\MaestroOrchid\Models;


use Orchid\Platform\Models\User as Authenticatable;

// Already used in Authenticatable
// use Orchid\Filters\Filterable;
// use Orchid\Screen\AsSource;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Notifications\Notifiable;

use App\Http\Traits\baseTrait;

// use in your model if need attachments (relationship with orchid's uploaded files model)
use Orchid\Attachment\Attachable;
// use in your model if need Api authentication 
use Laravel\Sanctum\HasApiTokens;

abstract class UserModel extends Authenticatable
{
    use baseTrait;

    // use Attachable;
    // use HasApiTokens;
}
