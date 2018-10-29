<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use SoftDeletes;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'plan_id',
		'user_id',
		'cancel_limit_at',
        'canceled_at',
		'trial_end_at',
		'start_at',
		'end_at',
    ];

    protected $dates = [
        'cancel_limit_at',
        'trial_end_at',
        'start_at',
        'end_at'
    ];

    protected $connection = 'core';
    protected $table = 'subscriptions';

    public function user()
    {
        return $this->belongsTo('App\Models\Core\User');
    }

    public function plan()
    {
        return $this->belongsTo('App\Models\Core\Plan');
    }

    public function activePlan()
    {
        return $this->plan()->active();
    }

    public function primaryPlan()
    {
        return $this->plan()->primary();
    }

    public function activePrimaryPlan()
    {
        return $this->plan()->active()->primary();
    }

    public function scopePrimary($query)
    {
        return $query->whereHas('plan', function($q){
            $q->primary();
        });
    }
    public function scopeActive($query)
    {
        return $query->whereHas('plan', function($q){
            $q->active();
        });
    }
    public function scopeActivePrimary($query)
    {
        return $query->whereHas('plan', function($q){
            $q->primary()->active();
        });
    }

    public function usageSubs()
    {
        return $this->hasMany('App\Models\Core\SubscriptionUsage', 'subs_id');
    }

    public function hasUsage()
    {
        $usage = $this->usageSubs();
        if ($usage->count() > 0) {
            return true;
        }
        return false;

    }

    /**
     * Return subscription trial status
     * @return bool
     */
    public function isOnTrial()
    {
        return now()->lessThan($this->trial_end_at);
    }

    /**
     * Return subscription can be cancel
     * @return bool
     */
    public function isCancelable()
    {
        return now()->lessThan($this->cancel_limit_at);
    }
}
