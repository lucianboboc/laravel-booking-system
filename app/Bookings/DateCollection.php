<?php

namespace App\Bookings;

use Illuminate\Support\Collection;

class DateCollection extends Collection
{
    public function firstAvailableDate()
    {
        return $this->first(fn (Date $date) => $date->slots->count() >= 1);
    }
}
