<?php

namespace App\Http\Controllers\Subscription;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Feature;
use App\Models\Core\Module;
use App\Models\Core\Plan;
use App\Http\Requests\Subs\NewPlanRequest;
use App\Models\Core\PlanPackage;
use App\Http\Requests\Subs\NewPlanPackageRequest;
use Exception;
use Validator;

class PlanPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PlanPackage::get();
    }

    /**
     * Tambah data planPackage
     * memvalidasi data, jika kolom name ada yang sama maka planPackage tidak akan terbuat
     * @param NewPlanPackageRequest $request
     * @return void
     */
    public function store(NewPlanPackageRequest $request)
    {   
        $validated = $request->validated();
        $reportError['plan'] = array();
        $reportError['module'] = array();
        $reportError['feature'] = array();
        $reportSuccess = array();
        
        #Plan object
        foreach ($request->plans as $keyPlan => $planData) {
            $validatedPlans = $validated['plans'][$keyPlan];

            $planValidator = Validator::make($planData, [
                'name' => 'required|unique:plans|max:255',
            ]);
            
            #validate plan    
            if ($planValidator->fails()) { 
                $plan = false;
                array_push($reportError['plan'], 'Plan <' . $planData['name'] . '> is exist');
            }
            else{ 
                #update sort
                $validatedPlans['sort'] = Plan::count() + 1;
                $plan = Plan::create($validatedPlans);
            }

            #Module object
            foreach ($planData['modules'] as $keyModule => $moduleData) {
                $validatedModules = $validatedPlans['modules'][$keyModule];
                $moduleValidator = Validator::make($moduleData, [
                    'name' => 'required|unique:modules|max:255',
                ]);
                #validate module 
                if ($moduleValidator->fails()) {
                    $module = false;
                    array_push($reportError['module'], 'Module <' . $moduleData['name'] . '> is exist');
                }
                else{
                    #update sort
                    $validatedModules['sort'] = Module::count() + 1;
                    $module = Module::create($validatedModules);
                }

                #Feature object
                foreach ($moduleData['features'] as $keyfeatures => $featureData) {
                    $validatedFeatures = $validatedModules['features'][$keyfeatures];
                    $featureValidator = Validator::make($featureData, [
                        'name' => 'required|unique:features|max:255',
                    ]);
                    #validate feature
                    if ($featureValidator->fails()) {
                        $feature = false;
                        array_push($reportError['feature'], 'Feature <' . $featureData['name'] . '> is exist');
                    }
                    else{
                        #update sort
                        $validatedFeatures['sort'] = Feature::count() + 1;
                        $feature = Feature::create($validatedFeatures);
                    }

                    #Buat planPackage setelah semua object tervalidasi
                    if($plan && $module && $feature){
                        $plan->module()->attach($module, ['feature_id' => $feature->id]);
                        array_push($reportSuccess, "Plan = " . $planData['name'] . ", Module = " . $moduleData['name'] . ", Feature = " . $featureData['name']);
                    }
                }
            }
        }

        $reportAll['error'] = $reportError;
        $reportAll['success'] = $reportSuccess;
        return ResponseHelper::json($reportAll, 200, 'New PlanPackage has been created');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $planPackage = PlanPackage::where('id', $id);
        if($planPackage->count() > 0){
            $planPackage->delete();
        }
        // $plan->module()->detach(24, ['feature_id' => [53]]);
    }
}
