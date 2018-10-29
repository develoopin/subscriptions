<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    const FEATURE_NAME = 'MODULE MANAGEMENT';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'lang',
        'value',
        'price',
        'is_premium',
        'is_active',
        'is_visible',
        'interval',
        'period',
        'sort',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * Boot function for using with User Events.
     *
     * @return void
     */
//    protected static function boot()
//    {
//        parent::boot();
//
//        static::saving(function ($model) {
//            if (! $model->interval) {
//                $model->interval = 'month';
//            }
//
//            if (! $model->interval_count) {
//                $model->interval_count = 1;
//            }
//        });
//    }

    /**
     * Get plan package features.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function planPackage()
    {
        return $this->hasMany('App\Models\Core\PlanPackage');
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
     * Get Interval Description
     *
     * @return string
     */
    public function getIntervalDescriptionAttribute()
    {
        return trans_choice('laraplans::messages.interval_description.' . $this->interval, $this->interval_count);
    }

    /**
     * Check if plan is free.
     *
     * @return boolean
     */
    public function isFree()
    {
        return ((float)$this->price <= 0.00);
    }

    /**
     * Get module is premuim.
     *
     * @return boolean
     */
    public function fPremium($query)
    {
        return $query->where('is_premium', 1);
    }

    /**
     * Check if module is active.
     *
     * @return boolean
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Check if module is premium.
     *
     * @return boolean
     */
    public function scopePremium($query)
    {
        return $query->where('is_premium', 1);
    }


    public function featureActive()
    {
        return $this->hasMany('App\Models\Core\Feature')->active();
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
        $feature = $this->features()->getEager()->first(function ($item) use ($code) {
            return $item->code === $code;
        });

        if (is_null($feature)) {
            throw new InvalidPlanFeatureException($code);
        }

        return $feature;
    }

    public function plan()
    {
        return $this->belongsToMany(Plan::class, 'plan_packages');
    }
}
