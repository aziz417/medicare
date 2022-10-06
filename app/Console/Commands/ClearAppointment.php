<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Illuminate\Console\Command;

class ClearAppointment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:appointment:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel all expire appointment!';

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
        $appointments = Appointment::where(function($query){
                $query->where('scheduled_at', '<=', now());
                $query->orWhere('created_at', '<=', now()->subHours(2));
            })
            ->whereIn('status', ['pending', 'waiting', 'submitted'])
            ->latest('scheduled_at')->get();
        $appointments->each(function($item){
            $item->update([
                'status' => "canceled",
                'comment' => "Canceled due to timeout, expire and due payment!"
            ]);
        });
        return 0;
    }
}
