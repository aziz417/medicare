<?php 
namespace App\Contracts;
/**
 * Activity Logger
 * 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
interface ActivityLogger
{
    /**
     *  Activity Log Type
     *
     * @return mixed
     */
    public function type(string $type = "BASIC");

    /**
     * Log The Activity
     *
     * @return mixed
     */
    public function user($user);
    
    /**
     * Log The Activity
     *
     * @return mixed
     */
    public function activity(string $content);

    /**
     * Log The Activity
     *
     * @return mixed
     */
    public function log($data = []);
}