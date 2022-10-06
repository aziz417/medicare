<?php 
namespace App\Services\SMS;

use App\Contracts\SmsService;

/**
 * Nexmo Sms 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
class Nexmo implements SmsService
{
    protected $config;
    function __construct()
    {
        $this->config = [
            'api_key' => "",
            'api_secret' => "",
            'to' => "",
            'text' => "",
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
        // $url = "https://rest.nexmo.com/sms/json?api_key=".urlencode($config['username'])."&api_secret=".urlencode($config['password'])."&to=".urlencode($config['to'])."&from=".urlencode($config['from'])."&text=".urlencode($config['message'])."";  
        $url = "https://rest.nexmo.com/sms/json";
        // $query = $this->getSerializeConfig();
        // $response = Http::get($url, $query);
        // return $response->json();   
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