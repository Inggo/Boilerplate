<?php

namespace Inggo\Boilerplate;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity as ActivityModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends ActivityModel
{
    protected $appends = [
        'details'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function getDetailsAttribute($value)
    {
        return $this->changes();
    }
}
