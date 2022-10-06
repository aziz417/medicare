<?php

namespace App\Services;

use App\Services\SMS\Jadu;
use App\Services\SMS\Nexmo;
use App\Services\SMS\LogDriver;
use Illuminate\Support\Manager;
use App\Services\SMS\Clickatell;
/**
 * SMS Manager
 * 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
class SmsManager extends Manager
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return settings('sms_default_gateway', env('SMS_DEFAULT_GATEWAY', 'log'));
    }

    /**
     * Creates a new SMS driver
     *
     * @return \App\Services\SMS\LogDriver
     */
    public function createLogDriver()
    {
        return new LogDriver();
    }

    /**
     * Creates a new SMS driver
     *
     * @return \App\Services\SMS\Jadu
     */
    public function createJaduDriver()
    {
        return new Jadu();
    }

    /**
     * Creates a new SMS driver
     *
     * @return \App\Services\SMS\Clickatell
     */
    public function createClickatellDriver()
    {
        return new Clickatell();
    }

    /**
     * Creates a new SMS driver
     *
     * @return \App\Services\SMS\Nexmo
     */
    public function createNexmoDriver()
    {
        return new Nexmo();
    }

    /**
     * Creates a new SMS driver
     *
     * @return \App\Services\SMS\Twilio
     */
    public function createTwilioDriver()
    {
        return new Twilio();
    }
}