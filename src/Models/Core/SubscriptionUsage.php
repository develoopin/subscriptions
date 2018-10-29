<?php

namespace App\Models\Core;

use Develoopin\Subscriptions\Period;
use Illuminate\Database\Eloquent\Model;

class SubscriptionUsage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subs_id',
        'usages',
        'price',
        'used_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];

    /**
     * Get plan features.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function features()
    {
        return $this->hasMany(config('laraplans.models.plan_feature'));
    }

    /**
     * Get plan subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(config('laraplans.models.plan_subscription'));
    }

    /**
     * Get Interval Name
     *
     * @return mixed string|null
     */
    public function getIntervalNameAttribute()
    {
        $intervals = Period::getAllIntervals();
        return (isset($intervals[$this->interval]) ? $intervals[$this->interval] : null);
    }

    /**
     * Check if plan is free.
     *
     * @return boolean
     */
    public function isFree()
    {
        return ((float) $this->price <= 0.00);
    }

    /**
     * Check if plan has trial.
     *
     * @return boolean
     */
    public function hasTrial()
    {
        return (is_numeric($this->trial_period_days) and $this->trial_period_days > 0);
    }

    /**
     * Returns the demanded feature
     *
     * @param String $code
     * @return PlanFeature
     * @throws InvalidPlanFeatureException
     */
    public function getFeatureByCode($code)
    {
        $feature = $this->features()->getEager()->first(function($item) use ($code) {
            return $item->code === $code;
        });

        if (is_null($feature)) {
            throw new InvalidPlanFeatureException($code);
        }

        return $feature;
    }
}
