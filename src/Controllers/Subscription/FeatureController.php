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
    protected $FEATURE_NAME = Feature::FEATURE_NAME;
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
        if (!\Gate::allows('lord-only', [$this->FEATURE_NAME, 'create'])) {
            return ResponseHelper::json('', 200, 'You cant access this page');
        }

        try {
            $validated = $request->validated();
            $validated['sort'] = Feature::count() + 1;
            $newPlan = Feature::create($validated);
            return ResponseHelper::json($newPlan, 200, 'New Feature has been created');
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
        if (!\Gate::allows('lord-only', [$this->FEATURE_NAME, 'update'])) {
            return ResponseHelper::json('', 200, 'You cant access this page');
        }

        try {
            $validated = $request->validated();
            $newPlan = Feature::find($id)->update($validated);
            return ResponseHelper::json($newPlan, 200, 'Feature has been updated');
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

        $features = Feature::whereId($id);
        if ($features->count() > 0) {
            $feature = $features->first();
            if ($feature->planPackage->count() == 0) {
                $sort = $feature->sort;
                $destroy = $features->delete();
                #update value all coloum sort
                Feature::where('sort', '>', $sort)->decrement('sort');
                return ResponseHelper::json($destroy, 200, 'Success delete feature');
            } else {
                return ResponseHelper::json($feature, 500, 'Cant delete, this plan still in used !');
            }
        }
        return ResponseHelper::json(null, 404, 'Cant delete, Feature not found !');
    }
}
