<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class PlanPackage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_id',
        'module_id',
        'feature_id',
        'sort',
    ];

    /**
     * Get plan features.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongTo
     */
    public function plan()
    {
        return $this->belongsTo('App\Models\Core\Plan');
    }

    /**
     * Get plan features.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongTo
     */
    public function module()
    {
        return $this->belongsTo('App\Models\Core\Module');
    }

    /**
     * Get features.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongTo
     */
    public function feature()
    {
        return $this->belongsTo('App\Models\Core\Feature');
    }
}
