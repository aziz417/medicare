<?php 
namespace App\Services\SMS;

use App\Contracts\SmsService;
use Illuminate\Support\Facades\Http;

/**
 * Jadu Sms 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
class Jadu implements SmsService
{
    protected $config;
    function __construct()
    {
        $this->config = [
            'api_key' => settings('sms_jadu_api_key'),
            'from' => settings('sms_jadu_sender'), //from
            'to' => "",
            'content' => "",
        ];
    }

    /**
     * Serialize config for api
     * @return string 
     */
    public function getSerializeConfig()
    {
        return [
            'api_key' => $this->config['api_key'],
            'smsType' => 'unicode',
            'maskingID' => trim($this->config['from']),
            'mobileNo' => $this->config['to'],
            'smsContent' => $this->config['content'],
        ];
    }

    /**
     * Trigger SMS to the user
     *
     * @return bool
     */
    public function send()
    {
        $url = "http://portal.jadusms.com/smsapi/masking";
        $query = $this->getSerializeConfig();
        $response = Http::get($url, $query);
        info(json_encode($response));
        return $response->json();
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