<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Privilege extends Model
{
    protected $table = 'privileges';
    protected $connection = 'core';
    protected $fillable = ['role_id', 'feature_id', 'view', 'create', 'edit', 'delete'];
    protected $prefix = 'can';

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }

    public function toWordFormat()
    {
        $privilege = [];
        $feature = $this->feature;
        if ($this->view) {
            array_push($privilege, $this->prefix . 'View' . studly_case($feature->name));
        }

        if ($this->create) {
            array_push($privilege, $this->prefix . 'Create' . studly_case($feature->name));
        }

        if ($this->edit) {
            array_push($privilege, $this->prefix . 'Edit' . studly_case($feature->name));
        }

        if ($this->delete) {
            array_push($privilege, $this->prefix . 'Delete' . studly_case($feature->name));
        }
        return $privilege;
    }

    public function isCreateAllowed()
    {
        return $this->create;
    }

    public function isUpdateAllowed()
    {
        return $this->edit;
    }

    public function isViewAllowed()
    {
        return $this->view;
    }

    public function isDeleteAllowed()
    {
        return $this->delete;
    }

    public function scopeFilterByFeatureName($query, $name)
    {
        return $query->whereHas('feature', function ($q) use ($name) {
            $q->where('name', $name);
        });
    }

}
