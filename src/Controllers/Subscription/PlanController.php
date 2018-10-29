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
    protected $FEATURE_NAME = Plan::FEATURE_NAME;
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
        if (!\Gate::allows('lord-only', [$this->FEATURE_NAME, 'create'])) {
            return ResponseHelper::json('', 200, 'You cant access this page');
        }

        try {
            $validated = $request->validated();
            $validated['sort'] = Plan::count() + 1;
            $newPlan = Plan::create($validated);
            return ResponseHelper::json($newPlan, 200, 'New Plan/Package has been created');
        } catch (Exception $e) {
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
        if (!\Gate::allows('lord-only', [$this->FEATURE_NAME, 'update'])) {
            return ResponseHelper::json('', 200, 'You cant access this page');
        }

        try {
            $validated = $request->validated();
            $newPlan = Plan::find($id)->update($validated);
            return ResponseHelper::json($newPlan, 200, 'New Plan/Package has been updated');
        } catch (Exception $e) {
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

        $plans = Plan::whereId($id);
        if ($plans->count() > 0) {
            $plan = $plans->first();
            if ($plan->planPackage->count() == 0) {
                $sort = $plan->sort;
                $destroy = $plans->delete();
                #update value all coloum sort
                Plan::where('sort', '>', $sort)->decrement('sort');
                return ResponseHelper::json($destroy, 200, 'Success delete plan');
            } else {
                return ResponseHelper::json($plan, 500, 'Cant delete, this plan still in used !');
            }
        }
        return ResponseHelper::json(null, 404, 'Cant delete, Plan not found !');
    }

    // public function find(Request $request)
	// {
	// 	return Plan::whereJsonContains('name->en', 'eng')->get();
	// }
}
