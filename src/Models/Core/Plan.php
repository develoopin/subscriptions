<?php

namespace App\Models\Core;

//use Develoopin\Subscriptions\Period;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
	const FEATURE_NAME = 'PLAN MANAGEMENT';


	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'description',
		'lang',
		'price',
		'value',
		'is_active',
		'trial_period',
		'trial_limit',
		'period',
		'interval',
		'grace_period',
		'grace_interval',
		'prorate_period',
		'prorate_interval',
		'canceled_period',
		'canceled_interval',
		'type',
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
//	protected static function boot()
//	{
//		parent::boot();
//
//		static::saving(function ($model) {
//			if (!$model->interval) {
//				$model->interval = 'month';
//			}
//
//			if (!$model->interval_count) {
//				$model->interval_count = 1;
//			}
//		});
//	}

	/**
	 * Get plan package features.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function planPackage()
	{
		return $this->hasMany('App\Models\Core\PlanPackage');
	}

	// /**
	//  * Get module premium.
	//  *
	//  * @return \Illuminate\Database\Eloquent\Relations\HasMany
	//  */
	// public function modulePremium()
	// {
	// 	return $this->hasMany('App\Models\Core\Module')->premium();
	// }

	/**
	 * Get plan subscriptions.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function subscriptions()
	{
		return $this->hasMany('App\Models\Core\Subscriptions');
	}

	/**
	 * Get Interval Name
	 *
	 * @return mixed string|null
	 */
//	public function getIntervalNameAttribute()
//	{
//		$intervals = Period::getAllIntervals();
//		return (isset($intervals[$this->interval]) ? $intervals[$this->interval] : null);
//	}

	/**
	 * Get Interval Description
	 *
	 * @return string
	 */
//	public function getIntervalDescriptionAttribute()
//	{
//		return trans_choice('laraplans::messages.interval_description.' . $this->interval, $this->interval_count);
//	}

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
	 * Check if plan has trial.
	 *
	 * @return boolean
	 */
	public function hasTrial()
	{
		return (is_numeric($this->trial_period) and $this->trial_period > 0);
	}

	public function scopeActive($query)
	{
		return $query->where('is_active', true);
	}

	public function scopePrimary($query)
	{
		return $query->where('type', 'primary');
	}

	/**
	 * Returns the demanded feature
	 *
	 * @param String $code
	 * @return PlanFeature
	 * @throws InvalidPlanFeatureException
	 */
//	public function getFeatureByCode($code)
//	{
//		$feature = $this->features()->getEager()->first(function ($item) use ($code) {
//			return $item->code === $code;
//		});
//
//		if (is_null($feature)) {
//			throw new InvalidPlanFeatureException($code);
//		}
//
//		return $feature;
//	}

	public function module()
	{
		return $this->belongsToMany(Module::class, 'plan_packages');
	}

	public function feature()
	{
		return $this->belongsToMany(Feature::class, 'plan_packages');
	}

}
