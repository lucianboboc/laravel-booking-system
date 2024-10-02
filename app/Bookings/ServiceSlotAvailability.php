<?php

namespace App\Bookings;

use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\Period\Boundaries;
use Spatie\Period\Period;
use Spatie\Period\PeriodCollection;
use Spatie\Period\Precision;

class ServiceSlotAvailability
{
    public function __construct(protected Collection $employees, protected Service $service)
    {
    }

    public function forPeriod(Carbon $startsAt, Carbon $endsAt)
    {
        $slotsDateRange = (new SlotRangeGenerator($startsAt, $endsAt))->generate($this->service->duration);

        $this->employees->each(function (Employee $employee) use ($startsAt, $endsAt, &$slotsDateRange) {
            $periods = (new ScheduleAvailability($employee, $this->service))
                ->forPeriod($startsAt, $endsAt);

            $periods = $this->removeAppointments($periods, $employee);

            foreach ($periods as $period) {
                $this->addAvailableEmployeeForPeriod($slotsDateRange, $period, $employee);
            }

            $slotsDateRange = $this->removeEmptySlots($slotsDateRange);
        });

        return $slotsDateRange;
    }

    protected function removeAppointments(PeriodCollection $periods, Employee $employee) {
        $employee->appointments->whereNull('cancelled_at')->each(function (Appointment $appointment) use (&$periods) {
            $periods = $periods->subtract(
                Period::make(
                    $appointment->starts_at->copy()->subMinutes($this->service->duration)->addMinute(),
                    $appointment->ends_at,
                    Precision::MINUTE(),
                    Boundaries::EXCLUDE_ALL()
                )
            );
        });

        return $periods;
    }

    protected function removeEmptySlots(Collection $range)
    {
        return $range->filter(function (Date $date) {
            $date->slots = $date->slots->filter(fn (Slot $slot) => $slot->hasEmployees());
            return true;    // use $date->hasSlots() if dates with no slots should be removed
        });
    }

    protected function addAvailableEmployeeForPeriod(Collection $slotsDateRange, Period $period, Employee $employee)
    {
        $slotsDateRange->each(function (Date $date) use ($period, $employee) {
            $date->slots->each(function (Slot $slot) use ($period, $employee) {
                if ($period->contains($slot->time)) {
                    $slot->addEmployee($employee);
                }
            });
        });
    }
}
