<?php

namespace App\Bookings;

use Illuminate\Support\Collection;

class DateCollection extends Collection
{
    public function firstAvailableDate()
    {
        return $this->first(fn (Date $date) => $date->slots->count() >= 1);
    }

    public function hasSlots()
    {
        return $this->filter(fn (Date $date) => $date->hasSlots());
    }
}
