<?php 
namespace App\Traits;

use App\Models\Appointment;

/**
 * UserRelationsHelper
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
trait UserRelationsHelper
{
    public function isSubmember()
    {
        return !empty($this->submember_of);
    }

    public function getCharge($type = 'booking')
    {
        $charge = $this->charges->where('type', $type)->first();
        return optional($charge);
    }

    public function getWallet()
    {
        if( $this->wallet ){
            return $this->wallet;
        }
        return $this->wallet()->create(['user_id' => $this->id, 'amount' => 0]);
    }

    public function getSlotsAttribute()
    {
        return $this->getSchedulesSlots();
    }
    public function getSchedulesSlots($date = false)
    {
        $slots = [];
        foreach ($this->schedules as $schedule) {
            foreach ($schedule->getTimeSlots() as $slot) {
                $item = [
                    'day' => $schedule->day,
                    'time' => $slot->format('H:i'),
                    'schedule_id' => $schedule->id,
                ];
                if( $date ){
                    $appointments = $this->doctorAppointments
                        ->whereIn('status', ['blocked', 'success', 'confirmed'])
                        ->where('scheduled_at', '>=', now())
                        ->pluck('scheduled_at')->toArray();
                    $date = _date($date)->format('Y-m-d');
                    $slotTime = "{$date} {$slot->format('H:i')}";
                    $item['booked'] = isSameDateTime($appointments, $slotTime);
                }
                $slots[] = $item;
            }
        }
        return $slots;
    }
}