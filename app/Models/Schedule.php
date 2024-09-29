<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getWorkingHoursForDate(Carbon $date)
    {
        $startsAt = strtolower($date->format('l')) . '_starts_at';
        $endsAt = strtolower($date->format('l')) . '_ends_at';

        $hours = [
            $this->$startsAt,
            $this->$endsAt,
        ];

        return array_filter($hours) ? $hours : null;
    }
}
