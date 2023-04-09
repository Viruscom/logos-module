<?php

namespace Modules\Logos\Models;

use App\Helpers\AdminHelper;
use App\Helpers\FileDimensionHelper;
use App\Traits\CommonActions;
use App\Traits\Scopes;
use App\Traits\StorageActions;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Logo extends Model implements TranslatableContract
{
    use Translatable, StorageActions, Scopes;
}
