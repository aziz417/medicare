<?php 
namespace App\Services\Logger;

use App\Contracts\ActivityLogger;
/**
 * NullLogger
 * This logger is not log anything, 
 * It just a dummy logger
 * 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */class NullLogger implements ActivityLogger
{
    /**
     *  Activity Log Type
     *
     * @return mixed
     */
    public function type(string $type = "BASIC")
    {
        return $this;
    }
    
    /**
     * Log The Activity
     *
     * @return mixed
     */
    public function user($user)
    {
        return $this;
    }

    /**
     * Log The Activity
     *
     * @return mixed
     */
    public function activity(string $content)
    {
        return $this;
    }

    /**
     * Log The Activity
     *
     * @return mixed
     */
    public function log($data = [])
    {
        return $this;
    }
}