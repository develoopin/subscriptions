<?php

namespace App\Http\Controllers\Subscription;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Plan;
use App\Http\Requests\Subs\NewPlanRequest;
use Exception;
use Illuminate\Validation\Validator;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Plan::get();
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
     * Store a newly created resource in storage.
     * @param NewPlanRequest $request
     * @return Exception|\Illuminate\Http\JsonResponse
     */
    public function store(NewPlanRequest $request)
    {
		try{
            $validated = $request->validated();
            $newPlan = Plan::create($validated);
            return ResponseHelper::json($newPlan, 200,'New Plan/Package has been created');
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(NewPlanRequest $request)
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
    public function update(NewPlanRequest $request, $id)
    {
        try{
            $validated = $request->validated();
            $newPlan = Plan::find($id)->update($validated);
            return ResponseHelper::json($newPlan, 200,'New Plan/Package has been updated');
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
