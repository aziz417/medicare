<?php

namespace App\Mail;

use App\Models\Template;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Helpers\AppMailMessage;
use Illuminate\Support\HtmlString;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPasswordResetEmailToDoctors extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $template;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $template)
    {
        $this->user = $user;
        $this->template = $template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $template = $this->template instanceof Template ? 
                    $this->template : 
                    (new Template)->fromArray($this->template);

        $username = explode('@', $this->user->email ?? 'app');
        $password = $username[0].'#new21';

        $this->user->update([
            "password" => bcrypt($password),
            "temp_password" => $password
        ]);
        $message = new AppMailMessage($template, $this->user, [
            '[[PASSWORD]]' => $password
        ]);

        $content = $message->render("notifications::email", [
            'template' => $this->template
        ]);
        
        $this->subject($this->template->subject);
        
        return $this->html($content);
    }
}