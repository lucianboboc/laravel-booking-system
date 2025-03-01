<?php

namespace App\Bookings;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class Date
{
    public Collection $slots;

    public function __construct(public Carbon $date)
    {
        $this->slots = collect();
    }

    public function addSlot(Slot $slot)
    {
        $this->slots->push($slot);
    }

    public function hasSlots() {
        return !$this->slots->isEmpty();
    }

    public function containsSlot(string $time)
    {
        return $this->slots->search(
            fn (Slot $slot) => $slot->time->format('H:i') === $time
        );
    }
}
