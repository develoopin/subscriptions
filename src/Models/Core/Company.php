<?php

namespace Develoopin\Subscriptions\Models\Core;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;
    protected $connection = 'core';
    protected $table = 'companies';
}
