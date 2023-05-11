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
use Modules\Icons\Models\Icon;
use Modules\Icons\Models\IconTranslation;

class Logo extends Model implements TranslatableContract
{
    use Translatable, StorageActions, Scopes;

    public const FILES_PATH                           = "logos";
    const        LOGOS_AFTER_DESCRIPTION              = 0;
    const        LOGOS_AFTER_ADDITIONAL_DESCRIPTION_1 = 1;
    const        LOGOS_AFTER_ADDITIONAL_DESCRIPTION_2 = 2;
    const        LOGOS_AFTER_ADDITIONAL_DESCRIPTION_3 = 3;
    const        LOGOS_AFTER_ADDITIONAL_DESCRIPTION_4 = 4;
    const        LOGOS_AFTER_ADDITIONAL_DESCRIPTION_5 = 5;
    const        LOGOS_AFTER_ADDITIONAL_DESCRIPTION_6 = 6;

    public static string $LOGO_SYSTEM_IMAGE  = 'logo_img.png';
    public static string $LOGO_RATIO         = '1/1';
    public static string $LOGO_MIMES         = 'jpg,jpeg,png,gif';
    public static string $LOGO_MAX_FILE_SIZE = '3000';

    public array $translatedAttributes = ['short_description'];
    protected    $table                = "logos";
    protected    $fillable             = ['module', 'model', 'model_id', 'active', 'main_position', 'position', 'filename'];
    public static function getCollections($parentModel): array
    {
        return [
            self::LOGOS_AFTER_DESCRIPTION              => self::getLogos($parentModel, self::LOGOS_AFTER_DESCRIPTION),
            self::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_1 => self::getLogos($parentModel, self::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_1),
            self::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_2 => self::getLogos($parentModel, self::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_2),
            self::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_3 => self::getLogos($parentModel, self::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_3),
            self::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_4 => self::getLogos($parentModel, self::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_4),
            self::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_5 => self::getLogos($parentModel, self::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_5),
            self::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_6 => self::getLogos($parentModel, self::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_6),
        ];
    }

    public static function getLogos($parentModel, $mainPosition)
    {
        return self::where('model', get_class($parentModel))
            ->where('model_id', $parentModel->id)
            ->where('main_position', $mainPosition)->where('active', true)->with('translations')->orderBy('position')->get();
    }
    public function getSystemImage(): string
    {
        return AdminHelper::getSystemImage(self::$LOGO_SYSTEM_IMAGE);
    }
    public function getFilepath($filename): string
    {
        return $this->getFilesPath() . $filename;
    }
    public function getFilesPath(): string
    {
        return self::FILES_PATH . '/' . $this->id . '/';
    }
    public static function getRequestData($request): array
    {
        $splitPath = explode("-", decrypt($request->path));
        if (is_null($request->position)) {
            $request['position'] = self::generatePosition($request);
        }

        $data = [
            'module'        => $splitPath[0],
            'model'         => $splitPath[1],
            'model_id'      => $splitPath[2],
            'main_position' => $request->main_position,
            'position'      => $request->position,
        ];

        $data['active'] = true;
        if ($request->has('active')) {
            $data['active'] = filter_var($request->active, FILTER_VALIDATE_BOOLEAN);
        }

        if ($request->has('filename')) {
            $data['filename'] = $request->filename;
        }

        if ($request->hasFile('image')) {
            $data['filename'] = pathinfo(CommonActions::getValidFilenameStatic($request->image->getClientOriginalName()), PATHINFO_FILENAME) . '.' . $request->image->getClientOriginalExtension();
        }

        return $data;
    }
    public static function generatePosition($request): int
    {
        $splitPath = explode("-", decrypt($request->path));

        $icons = self::where('module', $splitPath[0])
            ->where('model', $splitPath[1])
            ->where('model_id', $splitPath[2])
            ->where('main_position', $request->main_position)->orderBy('position', 'desc')->get();
        if (count($icons) < 1) {
            return 1;
        }
        if (!$request->has('position') || is_null($request['position'])) {
            return $icons->first()->position + 1;
        }

        if ($request['position'] > $icons->first()->position) {
            return $icons->first()->position + 1;
        }

        $iconsUpdate = self::where('module', $splitPath[0])
            ->where('model', $splitPath[1])
            ->where('model_id', $splitPath[2])
            ->where('main_position', $request->main_position)->where('position', '>=', $request['position'])->get();
        self::updateLogosPosition($iconsUpdate, true);

        return $request['position'];
    }
    private static function updateLogosPosition($icons, $increment = true): void
    {
        foreach ($icons as $iconUpdate) {
            $position = ($increment) ? $iconUpdate->position + 1 : $iconUpdate->position - 1;
            $iconUpdate->update(['position' => $position]);
        }
    }
    public static function getLangArraysOnStore($data, $request, $languages, $modelId, $isUpdate)
    {
        foreach ($languages as $language) {
            $data[$language->code] = LogoTranslation::getLanguageArray($language, $request, $modelId, $isUpdate);
        }

        return $data;
    }
}
