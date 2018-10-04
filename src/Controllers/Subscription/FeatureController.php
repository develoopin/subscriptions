<?php

namespace App\Http\Controllers\Subscription;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Subs\NewFeatureRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Feature;
use Exception;
use Illuminate\Validation\Validator;

class FeatureController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewFeatureRequest $request)
    {
        try{
            $validated = $request->validated();
            $newPlan = Feature::create($validated);
            return ResponseHelper::json($newPlan, 200,'New Feature has been created');
		}catch(Exception $e){
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

    public function edit(NewFeatureRequest $request)
    {
       //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NewFeatureRequest $request, $id) 
    {
        try{
            $validated = $request->validated();
            $newPlan = Feature::find($id)->update($validated);
            return ResponseHelper::json($newPlan, 200,'Feature has been updated');
		}catch(Exception $e){
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
}
