<?php

namespace Modules\Logos\Http\Requests;

use App\Helpers\LanguageHelper;
use Illuminate\Foundation\Http\FormRequest;

class LogoStoreRequest extends FormRequest
{
    public function __construct()
    {
        $this->LANGUAGES = LanguageHelper::getActiveLanguages();
    }
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        $this->trimInput();
        $array = [
            'image' => 'required'
        ];

        foreach ($this->LANGUAGES as $language) {
            $array['title_' . $language->code] = 'required';
        }

        return $array;
    }
    public function trimInput()
    {
        $trim_if_string = function ($var) {
            return is_string($var) ? trim($var) : $var;
        };
        $this->merge(array_map($trim_if_string, $this->all()));
    }
    public function messages()
    {
        $messages = [
            'image.required' => trans('logos::admin.logos.image_required')
        ];

        foreach ($this->LANGUAGES as $language) {
            $messages['title_' . $language->code . '.required'] = 'Полето за заглавие (' . $language->code . ') е задължително';
        }

        return $messages;
    }
}
