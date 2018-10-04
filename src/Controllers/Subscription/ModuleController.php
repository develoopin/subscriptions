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
        try{
            $mod= Module::create($request->validated());
            return ResponseHelper::json($mod,200, 'New Modules has been created');
        }catch (\Exception $e){
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
        //
        try{
            $mod= Module::find($request->id)->update($request->validated());
            return ResponseHelper::json($mod,200, 'Modules has been updated');
        }catch (\Exception $e){
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
        //
        try{
            $mod= Module::find($id)->update($request->validated());
            return ResponseHelper::json($mod,200, 'Modules has been updated');
        }catch (\Exception $e){
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
        //
    }

    public function getActive(){
       return Module::active()->get();
    }

    public function getFeatureActive($id){
        return Module::find($id)->featureActive()->get();
    }

    public function getPremiumModule()
    {   
        return Module::fPremium()->get();
    }
}
