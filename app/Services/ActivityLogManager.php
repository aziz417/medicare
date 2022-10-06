<?php

namespace App\Services;

use Illuminate\Support\Manager;
use App\Services\Logger\FileLogger;
use App\Services\Logger\NullLogger;
use App\Services\Logger\DatabaseLogger;
/**
 * Activity Log Manager
 * 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
class ActivityLogManager extends Manager
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return config('system.activity.logger', env('ACTIVITY_LOGGER', 'null'));
    }

    /**
     * Creates a new Logger driver
     *
     * @return \App\Services\Logger\NullLogger
     */
    public function createNullDriver()
    {
        return new NullLogger();
    }

    /**
     * Creates a new Logger driver
     *
     * @return \App\Services\Logger\FileLogger
     */
    public function createFileDriver()
    {
        return new FileLogger();
    }

    /**
     * Creates a new Logger driver
     *
     * @return \App\Services\Logger\DatabaseLogger
     */
    public function createDatabaseDriver()
    {
        return new DatabaseLogger();
    }
}