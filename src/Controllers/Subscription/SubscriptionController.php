<?php

namespace App\Http\Controllers\Subscription;

use App\Helpers\ResponseHelper;
use App\Helpers\OtherHelper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Plan;
use App\Models\Core\User;
use App\Models\Core\Subscription;
use App\Models\Core\SubscriptionUsage;
use App\Http\Requests\Subs\NewSubscriptionRequest;
use Exception;
use Illuminate\Validation\Validator;
use Carbon\Carbon;
use App\Traits\Subscribable;
// use App\Traits\commonTrait;

class SubscriptionController extends Controller
{
    use Subscribable;
    // use commonTrait;
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
    /**
     * Tambah data subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // new
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

    /**
     * New subscriptions function
     *
     * @param Request $request
     * @return void
     */
    public function newSubscription(Request $request)
    {
        $subs = User::find($request->user_id);
        $isSubscribe = $subs->isSubscriber($subs);

        if ($isSubscribe) {
            return 'already exist !';
        } else {
            $this->newSubcription($request);
        }

        return 'invalid';
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkEnd(Request $request)
    {
        return 234;
        $today = date('Y-m-d');
        $invoiceDate = '2018-10-27';
        $subs = Subscription::where('user_id', $request->user_id)->first();
        // dd(now()->equal('2018-10-04'));
        // dd(now()->subMonths(2)->toDateTimeString());
        // $subs->isCanceled();
        // $ar = [];
        // foreach($subs as $sub){
        //     array_push($ar, $sub->isCanceled());
        // }
        // return $ar;
        dd($subs->isCanceled());

        $subscription = Subscription::whereBetween('end_at', [$today, $invoiceDate])->get();
        return $subscription;
    }


    /**
     * Tambah data ke subscription usage
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkActive(Request $request)
    {
        $user = User::where('id', 2)->first();
        if ($user->isActive()) {
            // return Employee::get();
            $resultTemp = collect();
            // return $user->subscription;
            foreach ($user->subscription as $subs) {
                $notes = '';
                // dd(OtherHelper::isOnCancelLimit($subs->cancel_limit_at));
                // dd(OtherHelper::isOnCancelLimit($subs->cancel_limit_at) && OtherHelper::isOntrial($subs->trial_end_at));
                if (OtherHelper::isOnCancelLimit($subs->cancel_limit_at) && OtherHelper::isOntrial($subs->trial_end_at)) {
                    $invoice = Carbon::parse($subs->end_at)->subDay(7);
                    // dd(OtherHelper::isOnBilling($invoice, $subs->end_at));
                    if (OtherHelper::isOnBilling($invoice, $subs->end_at)) {
                        if ($subs->hasUsage() == false) {
                            $newSubsUsage = SubscriptionUsage::create([
                                'subs_id' => $subs->id,
                                'usages' => 1,
                                'price' => $subs->plan->price,
                                'used_at' => date('Y-m-d')
                            ]);
                            // return SubscriptionUsage::get();
                            $notes = "success add";
                        } else {
                            $notes = "subs usage exist";
                        }
                    } else {
                        $notes = "billing is not active";
                    }
                } else {
                    $notes = "Subscription ini masih dalam tahap trial";
                }
                $resultTemp->push($notes);
            }
            return $resultTemp;
        }
    }
}
