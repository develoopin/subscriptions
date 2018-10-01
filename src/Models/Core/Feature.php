<?php

namespace App\Models\Subscriptions\Core;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
	use  SoftDeletes;


	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'module_id',
		'name',
		'description',
        'is_premium',
        'is_active',
        'is_visible',
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
	 * Get plan features.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function features()
	{
		return $this->hasMany(config('descriptions.models.core.features'));
	}

	/**
	 * Get plan subscriptions.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function subscriptions()
	{
		return $this->hasMany(config('descriptions.models.core.subscriptions'));
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
}
