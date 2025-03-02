<?php

namespace App\Traits;

use Carbon\Carbon;

trait ModelExtTrait
{
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.Y h:i A');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.Y h:i A');
    }

}
