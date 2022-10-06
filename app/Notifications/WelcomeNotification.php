<?php

namespace App\Notifications;

use App\Models\Template;
use Illuminate\Bus\Queueable;
use App\Helpers\AppMailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeNotification extends Notification
{
    use Queueable;

    /**
     * The Database Template Name
     *
     * @var Template
     */
    public $template;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($template = null)
    {
        if( $template instanceof Template ){
            $this->template = $template;
        }else{
            $this->template = Template::where('id', $template)->orWhere('key', $template)->first();
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new AppMailMessage($this->template, $notifiable));
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
