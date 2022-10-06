<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Template;
use Illuminate\Console\Command;
use App\Jobs\PasswordResetEmailSenderJob;

class SendEmailToTheDoctor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user:send-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send new password email to the all doctors.';

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
        $doctors = User::whereRole('doctor')->get();
        $template = $this->getEmailTemplate();
        $sents = [];
        foreach ($doctors as $user) {
            if( $user->email && $template ){
                $sents[] = $user;
                PasswordResetEmailSenderJob::dispatch($user, $template);
            }
        }
        $total = count($sents);
        $this->info("Email send to all doctors ({$total}).");
        return 0;
    }

    public function getEmailTemplate()
    {
        $template = Template::where('key', 'SEND_PASSWORD_EMAIL_TO_DOCTORS')
                        ->withoutGlobalScope('hidden')->first();
        if( $template ){
            return $template;
        }
        return Template::create([
            'name' => "New Application Login Details Email",

            'subject' => "MedicsBD New Portal Credentials",
            'content' => "Hello [[USER_NAME]],
We are moved our old application to new one.
Your credentials for [[APP_NAME]] is here.
|> Email: [[USER_EMAIL]]/nlPassword: [[PASSWORD]]
",
            'action' => [
                "path" => "route:login",
                "title" => "Login"
            ],
            'after' => "For any kind of help or support please contact with us.",
            
            'hidden' => true,
            'key' => 'SEND_PASSWORD_EMAIL_TO_DOCTORS',
            'type' => 'email'
        ]);
    }
}
