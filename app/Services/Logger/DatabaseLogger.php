<?php 
namespace App\Services\Logger;

use App\Contracts\ActivityLogger;
use Illuminate\Support\Facades\DB;

/**
 * DatabaseLogger
 * 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
class DatabaseLogger implements ActivityLogger
{
    // Database Activity table
    protected $table = "activity_logs";
    // Log Data For User
    protected $user;
    // Log Type
    protected $type = "BASIC";
    // Log Content
    protected $content;
    // Additional Data
    protected $additional = [];

    // activity
    protected $activity = [];

    /**
     * Create the instance
     */
    function __construct()
    {
        $this->table = config('system.activity.table', 'activity_logs');
        $this->activity = [
            'created_at' => now()
        ];
    }

    /**
     *  Activity Log Type
     *
     * @return mixed
     */
    public function type(string $type = "BASIC")
    {
        $this->activity['type'] = $type;
        return $this;
    }

    /**
     * Log The Activity User
     *
     * @return mixed
     */
    public function user($user)
    {
        $this->activity['user_id'] = $user->id ?? $user;
        return $this;
    }

    /**
     * Log The Activity Content
     *
     * @return mixed
     */
    public function activity(string $content)
    {
        $this->activity['content'] = $content;
        return $this;
    }

    /**
     * Log The Activity Log
     *
     * @return mixed
     */
    public function log($data = [])
    {
        $this->activity['additional'] = is_array($data) ? json_encode($data) : $data;

        return DB::table($this->table)->insert($this->activity);
    }
}