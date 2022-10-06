<?php 
namespace App\Services\SMS;

use App\Contracts\SmsService;

/**
 * LogDriver SMS 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
class LogDriver implements SmsService
{
    protected $config = [];
    public function __construct()
    {
        if( ! file_exists(storage_path('logs/sms.log')) ){
            touch(storage_path('logs/sms.log'));
        }
    }

    /**
     * Trigger SMS to the user
     *
     * @return bool
     */
    public function send()
    {
        $oldContent = file_get_contents(storage_path('logs/sms.log'));
        $newContent = $this->serializeData($oldContent);
        return file_put_contents(storage_path('logs/sms.log'), $newContent);
    }
    /**
     * Serialize the content
     * @param  string $old 
     * @return string
     */
    public function serializeData($old)
    {
        $time = date('Y-m-d H:i:s');
        $content = is_string($this->config) ? 
                    $this->config : 
                    json_encode($this->config, JSON_PRETTY_PRINT);
        return "{$old}\n[{$time}]: {$content}\n";
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