<?php 
namespace App\Services\SMS;

use App\Contracts\SmsService;

/**
 * Clickatell Sms 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
class Clickatell implements SmsService
{
    protected $config;
    function __construct()
    {
        $this->config = [
            'apiKey' => "",
            'to' => "",
            'content' => "",
            'from' => "",
        ];
    }

    /**
     * Trigger SMS to the user
     *
     * @return bool
     */
    public function send()
    {
        // $url = "https://platform.clickatell.com/messages/http/send?apiKey=".urlencode($config['username'])."==&to=".urlencode($config['to'])."&content=".urlencode($config['message'])."&from=".urlencode($config['from'])."";
    }

    /**
     * Set the user
     *
     * @return $this
     */
    public function to($msisdn)
    {
        $this->config['to'] = $msisdn;
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