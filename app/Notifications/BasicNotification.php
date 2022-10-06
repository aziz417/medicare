<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class BasicNotification extends Notification
{
    use Queueable;

    protected $text;
    protected $data;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($text, array $data = [])
    {
        if( is_array($text) && isset($text['message']) ){
            $this->data = $text;
        }elseif( is_string($text) ){
            $data['message'] = $text;
            $this->data = $data;
        }else{
            $this->data = $data;
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
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
            'data'=> $this->data,
            'read_at' => $this->read_at ?? null
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->data ?? [];
    }
}
