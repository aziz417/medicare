<?php

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getOptions() as $key => $value) {
            Setting::setValue($key, $value);
        }
    }

    public function getOptions()
    {
        return [
            // Application Options
            'app_name' => config('app.name'), 
            'app_timezone' => config('app.timezone', 'UTC'), 
            'app_installed' => now()->format('Y-m-d H:i'), 
            // Email Options
            'email_smtp_host' => env('MAIL_HOST'), 
            'email_smtp_port' => env('MAIL_PORT'), 
            'email_smtp_user' => env('MAIL_USERNAME'), 
            'email_smtp_password' => env('MAIL_PASSWORD'), 
            'email_smtp_encription' => env('MAIL_ENCRYPTION'), 
            'email_from_address' => env('MAIL_FROM_ADDRESS'), 
            'email_from_name' => env('MAIL_FROM_NAME'), 
        ];
    }
}
