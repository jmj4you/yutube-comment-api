<?php

namespace App\Traits;

use Carbon\Carbon;

trait ModelExtTrait
{
    public function getCreatedAtAttribute($value)
    {
        // return Carbon::parse($value)->format('d.m.Y h:i A');
        return Carbon::parse($value)->diffForHumans();
    }

    public function getUpdatedAtAttribute($value)
    {
        // return Carbon::parse($value)->format('d.m.Y h:i A');
        return Carbon::parse($value)->diffForHumans();
    }

}
