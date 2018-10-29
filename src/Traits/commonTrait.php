<?php

namespace App\Traits;
use Carbon\Carbon;

trait commonTrait{

    public function isOntrial($trial_end_at){
        return Carbon::today()->gte($trial_end_at);
    }

    public function isOnCancelLimit($cancel_limit_at){
        return Carbon::today()->gte($cancel_limit_at);
    }

    public function isOnBilling($invoice, $end_at){
        return Carbon::today()->gte($invoice) && Carbon::today()->lte($end_at);
    }

}