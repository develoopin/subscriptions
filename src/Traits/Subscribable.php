<?php
namespace App\Traits;

use App\Models\Core\User;

trait Subscribable
{

    public function subscribe($plan)
    {
        if (is_numeric($plan) || is_string($plan)) {
            $plan = \App\Models\Core\Plan::findOrFail($plan);
        }
        return $plan;
        $subs = new \App\Models\Core\Subscription();

    }

    public function isOnplan($plan)
    {
        return Subscription::find($plan)->get();
    }

    public function isSubscriber($user = null)
    {
        // dd($user->id);
        $subscribe = null;
        if ($user) {
            $subscribe = Subscription::where('user_id', '=', $user->id)->get();
        }
        return $subscribe;
    }

    public function generateCancelLimit()
    {
        //from Core Config
        $cancelInterval = 'month';
        $cancelPeriod = 1;
        return \App\Helpers\PeriodHelper::calculate($cancelPeriod, $cancelInterval);
    }

    /**
     * Undocumented function
     *
     * @param [type] $plan
     * @param [type] $company
     * @return void
     */
    public function newSubcription($request)
    {

        $plan = Plan::find($request->plan_id);
        $carbon = now();

        $newSubscription = Subscription::create([
            'plan_id' => $request->plan_id,
            'user_id' => $request->user_id,
            'cancel_limit_at' => $carbon->addDays($plan->canceled_period)->toDateTimeString(),
            'trial_end_at' => $carbon->addDays($plan->trial_period)->toDateTimeString(),
            'start_at' => date('Y-m-d'),
            'end_at' => date('Y-m-') . '20'
        ]);
        return ResponseHelper::json($newSubscription, 200, 'You have successfully subscribed');

        try {
            $validated = $request->validated();
            $newSubscription = Subscription::create($validated);
            return ResponseHelper::json($newSubscription, 200, 'New Feature has been created');
        } catch (Exception $e) {
            return $e;
        }
    }

    public function isCanceled()
    {
        $canceled_date = auth()->user()->activePrimarySubscription()->first()->canceled_at;
        if (is_null($canceled_date)) {
            return false;
        }
        return now()->gte($canceled_date);
    }

    // public function isSubscriber()
    // {
    //     //validate user is client only
    //     if(auth()->user()->isClient())
    //     {
    //         return auth()->user()->isActive() && !$this->isCanceled();
    //     }
    //     return false;
    // }

}
