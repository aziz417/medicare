<?php 
namespace App\Services\Logger;

use App\Models\User;
use App\Contracts\ActivityLogger;
/**
 * FileLogger 
 * 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
class FileLogger implements ActivityLogger
{
    // Log Data For User
    protected $user;
    // Log Type
    protected $type = "BASIC";
    // Log Content
    protected $content;
    // Additional Data
    protected $additional = [];
    // Logger File Path
    protected $storage_path = 'logs/activity.log';
    protected $file_path;

    /**
     * Create the instance
     */
    function __construct()
    {
        if( ! file_exists(storage_path($this->storage_path)) ){
            touch(storage_path($this->storage_path));
        }
        $this->file_path = storage_path($this->storage_path);
    }

    /**
     *  Activity Log Type
     *
     * @return mixed
     */
    public function type(string $type = "BASIC")
    {
        $this->type = strtoupper($type);
        return $this;
    }

    /**
     * Log The Activity
     *
     * @return mixed
     */
    public function user($user)
    {
        $this->user = $user ?? auth()->user();
        return $this;
    }

    /**
     * Log The Activity
     *
     * @return mixed
     */
    public function activity(string $content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Log The Activity
     *
     * @return mixed
     */
    public function log($data = [])
    {
        $this->additional = $data;
        return $this->writeFile();
    }

    /**
     * Write to the log file
     * 
     * @return boolean
     */
    private function writeFile()
    {
        $oldContent = file_get_contents($this->file_path);
        $newContent = $this->serializeData($oldContent);
        return file_put_contents($this->file_path, $newContent);
    }

    /**
     * Serialize the content
     * @param  string $old 
     * @return string
     */
    private function serializeData($old)
    {
        $time = date('Y-m-d H:i:s');
        $user_id = ($this->user instanceof User) ? $this->user->id : $this->user;
        $additional = json_encode($this->additional);
        return "{$old}[{$user_id}][{$this->type}][{$time}]: {$this->content}|{$additional}\n";
    }
}