<?php

namespace Modules\Logos\Models;

use App\Helpers\AdminHelper;
use App\Helpers\FileDimensionHelper;
use App\Helpers\UrlHelper;
use App\Interfaces\Models\CommonModelInterface;
use App\Interfaces\Models\CommonModelTranslationInterfaces;
use App\Interfaces\Models\ImageModelInterface;
use App\Models\Language;
use App\Traits\CommonActions;
use App\Traits\Scopes;
use App\Traits\StorageActions;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Catalogs\Models\Catalog;
use Modules\Team\Models\Team;
use Modules\Team\Models\TeamDivision;
use Modules\Team\Models\TeamTranslation;

class LogoTranslation  extends Model implements CommonModelTranslationInterfaces
{
    use StorageActions;

    protected $table    = "logo_translation";
    protected $fillable = ['locale', 'logo_id', 'title', 'url', 'short_description'];
    public static function getLanguageArray($language, $request, $modelId, $isUpdate): array
    {
        $data = [
            'locale' => $language->code,
            'title'  => $request['title_' . $language->code],
            'url'    => $request['url_' . $language->code]
        ];

        if ($request->has('short_description_' . $language->code)) {
            $data['short_description'] = $request['short_description_' . $language->code];
        }

        return $data;
    }
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Logo::class, 'logo_id');
    }
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
