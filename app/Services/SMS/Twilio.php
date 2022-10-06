<?php 
namespace App\Services\SMS;

use Twilio\Rest\Client;
use App\Contracts\SmsService;

/**
 * Twilio SMS
 * 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
class Twilio implements SmsService
{
    protected $config;
    protected $client;
    function __construct()
    {
        $this->config = [
            'sid' => settings('sms_twilio_sid', config('services.sms.twilio.sid')),
            'token' => settings('sms_twilio_token', config('services.sms.twilio.token')),
            'from' => settings('sms_twilio_sender', config('services.sms.twilio.sender')), //from
            'to' => "",
            'content' => "",
        ];
        $this->client = new Client($this->config['sid'], $this->config['token']);
    }

    /**
     * Trigger SMS to the user
     *
     * @return bool
     */
    public function send()
    {
        try {
            if( $this->config['to'] ){
                return $this->client->messages->create(
                    $this->config['to'],
                    [
                        'from' => $this->config['from'],
                        'body' => $this->config['content']
                    ]
                );
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Set the user
     *
     * @return $this
     */
    public function to($msisdn)
    {
        preg_match('/^(?:\\+880|880|0)?(1[3-9]\d{8})$/', $msisdn, $output);
        $validMsisdn = '880'.$output[1];
        $this->config['to'] = $validMsisdn;
        return $this;
    }

    /**
     * Set the content
     *
     * @return $this
     */
    public function content($text)
    {
        $this->config['content'] = $text;
        return $this;
    }
}