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
		'trial_end_at',
		'start_at',
		'end_at',
    ];
    
    protected $connection = 'core';
    protected $table = 'subscriptions';
}
