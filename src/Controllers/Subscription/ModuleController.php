<?php

namespace App\Http\Controllers\Subscription;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Subs\NewModuleRequest;
use App\Models\Core\Module;
use App\Models\Core\Feature;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Validation\Validator;

class ModuleController extends Controller
{
    protected $FEATURE_NAME = Module::FEATURE_NAME;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @param NewModuleRequest $request
     * @return \Exception|\Illuminate\Http\JsonResponse
     */
    public function store(NewModuleRequest $request)
    {
        if (!\Gate::allows('lord-only', [$this->FEATURE_NAME, 'create'])) {
            return ResponseHelper::json('', 200, 'You cant access this page');
        }

        try {
            $validated = $request->validated();
            $validated['sort'] = Module::count() + 1;
            $newModule = Module::create($validated);
            return ResponseHelper::json($newModule, 200, 'New Modules has been created');
        } catch (\Exception $e) {
            return $e;
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(NewModuleRequest $request)
    {
        if (!\Gate::allows('lord-only', [$this->FEATURE_NAME, 'update'])) {
            return ResponseHelper::json('', 200, 'You cant access this page');
        }

        try {
            $mod = Module::find($request->id)->update($request->validated());
            return ResponseHelper::json($mod, 200, 'Modules has been updated');
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NewModuleRequest $request, $id)
    {
        if (!\Gate::allows('lord-only', [$this->FEATURE_NAME, 'update'])) {
            return ResponseHelper::json('', 200, 'You cant access this page');
        }

        try {
            $mod = Module::find($id)->update($request->validated());
            return ResponseHelper::json($mod, 200, 'Modules has been updated');
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!\Gate::allows('lord-only', [$this->FEATURE_NAME, 'delete'])) {
            return ResponseHelper::json('', 200, 'You cant access this page');
        }

        $modules = Module::whereId($id);
        if ($modules->count() > 0) {
            $module = $modules->first();
            if ($module->planPackage->count() == 0) {
                $sort = $module->sort;
                $destroy = $modules->delete();
                #update value all coloum sort
                Module::where('sort', '>', $sort)->decrement('sort');
                return ResponseHelper::json($destroy, 200, 'Success delete module');
            } else {
                return ResponseHelper::json($module, 500, 'Cant delete, this plan still in used !');
            }
        }
        return ResponseHelper::json(null, 404, 'Cant delete, Module not found !');
    }

    public function getActive()
    {
        return Module::active()->get();
    }

    public function getFeatureActive($id)
    {
        return Module::find($id)->featureActive()->get();
    }

    public function getPremiumModule()
    {
        return Module::fPremium()->get();
    }
}
