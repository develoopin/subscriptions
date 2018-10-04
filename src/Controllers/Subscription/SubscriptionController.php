<?php

namespace App\Http\Controllers\Subscription;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Plan;
use App\Models\Core\Subscription;
use App\Http\Requests\Subs\NewSubscriptionRequest;
use Exception;
use Illuminate\Validation\Validator;
use Carbon\Carbon;

class SubscriptionController extends Controller
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
    public function store(Request $request)
    {   
        $plan = Plan::find(2);
        $carbon = Carbon::today();
        // return $carbon->addDays($plan->canceled_period)->toDateTimeString();
        $newSubscription = Subscription::create([
                'plan_id' => 2,
                'user_id' => 48,
                'cancel_limit_at' => $carbon->addDays($plan->canceled_period)->toDateTimeString(),
                'start_at' => date('Y-m-d'),
                'end_at' => date('Y-m-') . '20'
            ]);
        return ResponseHelper::json($newSubscription, 200, 'You have successfully subscribed');

        try{
            $validated = $request->validated();
            $newSubscription = Subscription::create($validated);
            return ResponseHelper::json($newSubscription, 200,'New Feature has been created');
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

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
