<?php

namespace App\Console\Commands;

use App\Models\Template;
use App\Models\Appointment;
use Illuminate\Console\Command;

class AppointmentReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:appointment:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify the appointment user before the appointment start!';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $appointments = Appointment::whereBetween('scheduled_at', 
                [now()->subMinutes(5), now()->addMinute(5) ]
            )
            ->whereIn('status', ['blocked', 'success', 'approved', 'confirmed'])
            ->where('notified', false)
            ->latest('scheduled_at')->get();
        info("Running Appointment Notifier!");
        $template = Template::getTemplate('NOTIFY_UPCOMING_APPOINTMENT_USER');
        $appointments->each(
            function($item) use ($template){
                $user = $item->patient;
                $content = $template->compiled([
                    '[[APPOINTMENT_ID]]' => $item->id,
                    '[[APPOINTMENT_CODE]]' => $item->appointment_code,
                    '[[APPOINTMENT_TIME]]' => $item->scheduled_at->format('h:i A'),
                    '[[APPOINTMENT_DATE]]' => $item->scheduled_at->format('D - d M, Y'),
                    '[[APPOINTMENT_DATETIME]]' => $item->scheduled_at->format('d M, Y - h:i A'),
                ], $user);
                if( $user->mobile ){
                    app('SMS')->to($user->mobile)->content($content)->send();
                    activity_log("Send Appointment {$item->appointment_code} SMS Notification", $user, [
                        'type' => "SMS",
                        'env' => 'Console', 
                        'logger' => 'scheduler'
                    ]);
                    $item->update(['notified' => true]);
                }
            }
        );
        return 0;
    }
}
