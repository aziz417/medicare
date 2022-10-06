<?php

namespace App\Contracts;

/**
 * SMS Service 
 * 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
interface SmsService
{
    /**
     * Trigger SMS to the user
     *
     * @return bool
     */
    public function send();

    /**
     * Set the user
     *
     * @return $this
     */
    public function to($msisdn);

    /**
     * Set the content
     *
     * @return $this
     */
    public function content($text);

}
