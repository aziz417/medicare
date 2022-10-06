<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class StartCall extends Notification
{
    use Queueable;

    public $appointment;
    public $force;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($appointment, $force = false)
    {
        $this->appointment = $appointment;
        $this->force = $force;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast'];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'result'=> $this->getShareableData(),
            'start_by' => auth()->id() == $this->appointment->doctor_id ? 'doctor' : 'patient'
        ]);
    }

    protected function getShareableData()
    {
        $md5key = $this->appointment->doctor_id==$this->force ? $this->appointment->doctor_id : $this->force;
        $routeArgs = ['appointment' => $this->appointment->id, 'user' => auth()->id()];
        if( $this->force ){
            $routeArgs['force_token'] = md5($md5key);
        }
        return [
            'appointment'=> $this->appointment->only('id', 'appointment_code', 'user_id', 'doctor_id', 'scheduled_at', 'type', 'status'),
            'patient' => $this->appointment->patient->getPublicData(),
            'doctor' => $this->appointment->doctor->getPublicData(),
            'join_url' => route('common.video.call', $routeArgs),
            'force' => $this->force ? md5($md5key) : null
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
