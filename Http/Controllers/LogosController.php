<?php

namespace Modules\Logos\Http\Controllers;

use App\Actions\CommonControllerAction;
use App\Helpers\AdminHelper;
use App\Helpers\FileDimensionHelper;
use App\Helpers\LanguageHelper;
use App\Helpers\MainHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Icons\Models\Icon;
use Modules\Logos\Http\Requests\LogoStoreRequest;
use Modules\Logos\Http\Requests\LogoStoreToManyPages;
use Modules\Logos\Http\Requests\LogoUpdateRequest;
use Modules\Logos\Models\Logo;
use Modules\Logos\Models\LogoTranslation;

class LogosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        $data = AdminHelper::getInternalLinksUrls([]);

        return view('logos::admin.logos.index', $data);
    }

    public function getEncryptedPath(Request $request)
    {
        return encrypt($request->moduleName . '-' . $request->modelPath . '-' . $request->modelId);
    }


    public function loadIconsPage($path)
    {
        $splitPath = explode("-", decrypt($path));

        $modelClass = $splitPath[1];
        if (!class_exists($modelClass)) {
            return view('logos::admin.logos.error_show');
        } else {
            $modelInstance = new $modelClass;
            $modelConstant = get_class($modelInstance) . '::ALLOW_LOGOS';
            if (!defined($modelConstant) || !constant($modelConstant)) {
                return view('logos::admin.logos.error_show');
            }

            $model = $modelClass::where('id', $splitPath[2])->first();
            if (is_null($model)) {
                return view('logos::admin.logos.error_show');
            }
            $languages      = LanguageHelper::getActiveLanguages();
            $model['Logos'] = Logo::getCollections($model);

            return view('logos::admin.logos.show', ['moduleName' => $splitPath[0], 'modelPath' => $modelClass, 'model' => $model, 'languages' => $languages]);
        }
    }


    public function create($path)
    {
        $pathHash = $path;
        if ($pathHash == null) {
            return back()->withErrors([trans('logos::admin.logos.page_not_found')]);
        }
        $splitPath = explode("-", decrypt($pathHash));

        $modelClass = $splitPath[1];
        if (!class_exists($modelClass)) {
            return back()->withErrors([trans('logos::admin.logos.page_not_found')]);
        } else {
            $modelInstance = new $modelClass;
            $modelConstant = get_class($modelInstance) . '::ALLOW_LOGOS';
            if (!defined($modelConstant) || !constant($modelConstant)) {
                return back()->withErrors([trans('logos::admin.logos.icons_not_allowed')]);
            }

            $model = $modelClass::where('id', $splitPath[2])->first();
            if (is_null($model)) {
                return back()->withErrors([trans('logos::admin.logos.page_not_found')]);
            }

            $data = [
                'languages'     => LanguageHelper::getActiveLanguages(),
                'path'          => $pathHash,
            ];
            $data = AdminHelper::getInternalLinksUrls($data);

            return view('logos::admin.logos.create', $data);
        }
    }

    public function store(LogoStoreRequest $request, CommonControllerAction $action)
    {
        $splitPath  = explode("-", decrypt($request->path));
        $modelClass = $splitPath[1];
        if (!class_exists($modelClass)) {
            return redirect()->back()->withErrors(['logos::admin.logos.page_not_found']);
        }

        $logo = $action->doSimpleCreate(Logo::class, $request);
        $logo->storeAndAddNew($request);

        return redirect()->route('admin.logos.manage.load-logos', ['path' => $request->path])->with('success-message', trans('admin.common.successful_create'));
    }

    public function edit($id)
    {
        $logo = Logo::whereId($id)->with('translations')->first();
        MainHelper::goBackIfNull($logo);

        $data = [
            'icon'          => $logo,
            'languages'     => LanguageHelper::getActiveLanguages()
        ];
        $data = AdminHelper::getInternalLinksUrls($data);

        return view('logos::admin.logos.edit', $data);
    }

    public function update($id, LogoUpdateRequest $request, CommonControllerAction $action): RedirectResponse
    {
        $logo = Logo::whereId($id)->with('translations')->first();
        MainHelper::goBackIfNull($logo);

        $request['path'] = encrypt($logo->module . '-' . $logo->model . '-' . $logo->model_id);
        $action->doSimpleUpdate(Logo::class, LogoTranslation::class, $logo, $request);

        if ($request->has('image')) {
            $logo->saveFile($request->image);
        }

        return redirect()->route('admin.logos.manage.load-logos', ['path' => $request->path])->with('success-message', 'admin.common.successful_edit');
    }

    public function deleteMultiple(Request $request, CommonControllerAction $action): RedirectResponse
    {
        if (!is_null($request->ids[0])) {
            $ids = array_map('intval', explode(',', $request->ids[0]));
            foreach ($ids as $id) {
                $logo = Logo::find($id);
                if (is_null($logo)) {
                    continue;
                }

                if ($logo->existsFile($logo->filename)) {
                    $logo->deleteFile($logo->filename);
                }

                $modelsToUpdate = Logo::where('module', $logo->module)->where('model', $logo->model)->where('model_id', $logo->model_id)->where('main_position', $logo->main_position)->where('position', '>', $logo->position)->get();
                $logo->delete();
                foreach ($modelsToUpdate as $modelToUpdate) {
                    $modelToUpdate->update(['position' => $modelToUpdate->position - 1]);
                }
            }

            return redirect()->back()->with('success-message', 'admin.common.successful_delete');
        }

        return redirect()->back()->withErrors(['admin.common.no_checked_checkboxes']);
    }
    public function delete($id): RedirectResponse
    {
        $logo = Logo::where('id', $id)->first();
        MainHelper::goBackIfNull($logo);

        $modelsToUpdate = Logo::where('module', $logo->module)->where('model', $logo->model)->where('model_id', $logo->model_id)->where('main_position', $logo->main_position)->where('position', '>', $logo->position)->get();
        $logo->delete();
        foreach ($modelsToUpdate as $currentModel) {
            $currentModel->update(['position' => $currentModel->position - 1]);
        }

        return redirect()->back()->with('success-message', 'admin.common.successful_delete');
    }

    public function activeMultiple($active, Request $request, CommonControllerAction $action): RedirectResponse
    {
        $action->activeMultiple(Logo::class, $request, $active);

        return redirect()->back()->with('success-message', 'admin.common.successful_edit');
    }
    public function active($id, $active): RedirectResponse
    {
        $logo = Logo::find($id);
        MainHelper::goBackIfNull($logo);

        $logo->update(['active' => $active]);

        return redirect()->back()->with('success-message', 'admin.common.successful_edit');
    }

    public function positionUp($id, CommonControllerAction $action): RedirectResponse
    {
        $logo = Logo::whereId($id)->first();
        MainHelper::goBackIfNull($logo);

        $previousModel = Logo::where('module', $logo->module)->where('model', $logo->model)->where('model_id', $logo->model_id)->where('main_position', $logo->main_position)->where('position', $logo->position - 1)->first();
        if (!is_null($previousModel)) {
            $previousModel->update(['position' => $previousModel->position + 1]);
            $logo->update(['position' => $logo->position - 1]);
        }

        return redirect()->back()->with('success-message', 'admin.common.successful_edit');
    }

    public function positionDown($id, CommonControllerAction $action): RedirectResponse
    {
        $logo = Logo::whereId($id)->first();
        MainHelper::goBackIfNull($logo);

        $nextModel = Logo::where('module', $logo->module)->where('model', $logo->model)->where('model_id', $logo->model_id)->where('main_position', $logo->main_position)->where('position', $logo->position + 1)->first();
        if (!is_null($nextModel)) {
            $nextModel->update(['position' => $nextModel->position - 1]);
            $logo->update(['position' => $logo->position + 1]);
        }

        return redirect()->back()->with('success-message', 'admin.common.successful_edit');
    }

    public function toManyPagesCreate()
    {
        $data = [
            'languages'     => LanguageHelper::getActiveLanguages()
        ];
        $data = AdminHelper::getInternalLinksUrls($data);

        return view('logos::admin.logos.add_to_many_pages', $data);
    }

    public function storeToManyPages(LogoStoreToManyPages $request, CommonControllerAction $action)
    {
        $data     = json_decode($request->pagesIds, true);
        foreach ($data as $item) {
            $request['path']        = encrypt($item['module'] . '-' . $item['model'] . '-' . $item['model_id']);
            $request['position']    = Logo::generatePosition($request);
            $request['module']      = $item['module'];
            $request['model']       = $item['model'];
            $request['model_id']    = $item['model_id'];
            $request['icon_set_id'] = 0;
            $icon                   = $action->doSimpleCreate(Icon::class, $request);

            $icon->saveFile($request->file('image'));
        }

        return redirect('admin.logos.index')->with('success-message', 'admin.common.successful_create');
    }
}
