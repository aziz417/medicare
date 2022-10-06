<?php 

use App\Models\User;
use App\Models\Setting;
use Carbon\CarbonPeriod;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Notifications\BasicNotification;

/**
 * Helper Functions
 * 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */

/* @function html_string()  @version v1.0  @since 1.0.0 */
if (!function_exists('html_string')) {
    function html_string($string)
    {
        return new HtmlString($string);
    }
}

/* @function has_route()  @version v1.0  @since 1.0.0 */
if (!function_exists('has_route')) {
    function has_route($route)
    {
        return Route::has($route);
    }
}

/* @function _date()  @version v1.0  @since 1.0 */
if (!function_exists('_date')) {
    function _date($date, $format = null)
    {
        $date = ($date instanceof Carbon) ? $date : Carbon::parse($date);
        if($format){
            return $date->format($format);
        }
        return $date;
    }
}

/* @function isDay()  @version v1.0  @since 1.0 */
if (!function_exists('isDay')) {
    function isDay($name, $fallback = null)
    {
        if( $fallback ){
            return date('l') == $name ? $fallback : false;
        }
        return date('l') == $name;
    }
}

/* @function is_json()  @version v1.0  @since 1.0 */
if (!function_exists('is_json')) {
    function is_json($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}

/* @function word_limit()  @version v.1.0  @since 1.0 */
if (!function_exists('word_limit')) {
    /*==== Return some words from a sentence ====*/
    function word_limit($sentence, $limit=10, $end='...'){
        if (!empty($sentence)){
            $words = explode(" ",$sentence);
            if( count($words) <= $limit ) return $sentence;
            $sentence = implode(" ",array_splice($words,0,$limit)) . $end;
        }
        return $sentence;
    }
}

/* @function if_null()  @version v1.0  @since 1.0 */
if (!function_exists('if_null')) {
    function if_null($string, $if_null = null)
    {
        return is_null($string) && empty($string) ? $if_null : $string;
    }
}
/* @function doWhen()  @version v1.0  @since 1.0 */
if (!function_exists('doWhen')) {
    function doWhen($string, $callback)
    {
        if( ! empty($string) && is_callable($callback) ){
            return $callback($string);
        }
        return null;
    }
}

/* @function has_file()  @version v.1.0  @since 1.0 */
if (!function_exists('has_file')) {
    function has_file($file) {
        if(file_exists($file) && is_file($file) && !is_dir($file) ){
            return true;
        }else{
            return false;
        }
    }
}

/* @function save_file()  @version v.1.0  @since 1.0 */
if (!function_exists('save_file')) {
    function save_file($file, $path = '', $name = null) {
        if( ! $file instanceof UploadedFile) return null;
        $ext = $file->extension();
        $file_name = if_null($name, str()->random(20)).'.'.$ext;
        $path = $file->storeAs($path, $file_name, 'assets');
        return $path;
    }
}

/* @function delete_file()  @version v.1.0  @since 1.0 */
if (!function_exists('delete_file')) {
    function delete_file($file_path) {
        if( has_file($file_path) ){
            try {
                return unlink($file_path);
            } catch (\Exception $e) {
                info($e->getMessage());
            }
        }
    }
}

/* @function storage_public_path()  @version v.1.0  @since 1.0 */
if (!function_exists('storage_public_path')) {
    function storage_public_path($path = '') {
        return storage_path('app/public').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

/* @function storage_url()  @version v.1.0  @since 1.0 */
if (!function_exists('storage_url')) {
    function storage_url($file, $default = null) {
        if( has_file(storage_path('app/'.$file)) ){
            return Storage::get($file);
        }else{
            return $default;
        }
    }
}

/* @function public_file_url()  @version v.1.0  @since 1.0 */
if (!function_exists('public_file_url')) {
    function public_file_url($file, $default = null) {
        if( has_file(storage_path('app/public/'.$file)) ){
            return asset(Storage::url($file));
        }else{
            return $default;
        }
    }
}

/* @function filterValue()  @version v1.0  @since 1.0 */
if (!function_exists('filterValue')) {
    function filterValue($value)
    {
        if( is_null($value) ) return $value;
        $bool = filter_var($value, 
            FILTER_VALIDATE_BOOLEAN,
            FILTER_NULL_ON_FAILURE);
        if( $bool === null ){
            return $value;
        }
        return $bool;
    }
}

/* @function str()  @version v1.0  @since 1.0.0 */
if (!function_exists('str')) {
    function str()
    {
        return new Str;
    }
}
/* @function arr()  @version v1.0  @since 1.0.0 */
if (!function_exists('arr')) {
    function arr()
    {
        return new Arr;
    }
}
/* @function slug2title()  @version v1.0  @since 1.0.0 */
if (!function_exists('slug2title')) {
    function slug2title($text)
    {
        return str()->title(str_replace(['-', '_'], [' ', ' '], $text));
    }
}

/* @function activity_log()  @version v1.0  @since 1.0.0 */
if (!function_exists('activity_log')) {
    function activity_log($content, $user = null, $others = [])
    {
        $type = $others['type'] ?? 'BASIC';
        unset($others['type']);
        try {
            return  app('ActivityLog')//->driver('database')
                ->user($user)
                ->type($type)
                ->activity($content)
                ->log($others);
        } catch (\Exception $e) {
            debug($e);
        }
    }
}
/* @function sendNotification()  @version v1.0  @since 1.0.0 */
if (!function_exists('sendNotification')) {
    function sendNotification(User $user, $text, $data = [])
    {
        $user->notify(new BasicNotification($text, $data));
    }
}

/* @function debug()  @version v1.0  @since 1.0.0 */
if (!function_exists('debug')) {
    function debug($exception = null)
    {
        $is_debug = config('app.debug') && config('app.env') != 'production';
        if( $exception && $is_debug ){
            throw $exception;
        }
        return $is_debug;
    }
}

/* @function pipe2array()  @version v1.0  @since 1.0.0 */
if (!function_exists('pipe2array')) {
    function pipe2array($string)
    {
        return explode('|', $string);
    }
}

/* @function autop()  @version v1.0  @since 1.0.0 */
if (!function_exists('autop')) {
    function autop($string)
    {
        return str_replace(["\n"], ['<br>'], $string);
    }
}

/* @function inCurrency()  @version v1.0  @since 1.0.0 */
if (!function_exists('inCurrency')) {
    function inCurrency($string)
    {
        $symbol = config('system.currency.symbol', '$');
        return "{$symbol}{$string}";
    }
}

/* @function validateMobile()  @version v1.0  @since 1.0.0 */
if (!function_exists('validateMobile')) {
    function validateMobile($msisdn)
    {
        if(preg_match('/^(?:\\+880|880|0)?(1[3-9]\d{8})$/', $msisdn, $output)){
            $msisdn = '0'.$output[1];
        }
        return $msisdn;
    }
}

/* @function date_range()  @version v1.0  @since 1.0.0 */
if (!function_exists('date_range')) {
    function date_range($first, $addDay = 6, $interval = 'P1D') {
        $last = _date($first->format('Y-m-d'))->addDays($addDay);
        $period = CarbonPeriod::create($first, $last)->setDateInterval($interval);
        return $period;
    }
}

/* @function isSameDateTime()  @version v1.0  @since 1.0.0 */
if (!function_exists('isSameDateTime')) {
    function isSameDateTime(array $dateTimesArray, $checkDateTime) {
        $check = array_map(function($item)use($checkDateTime){
            if( $item instanceof Carbon ){
                return $item->isSameMinute($checkDateTime);
            }
            return Carbon::parse($item)->isSameMinute($checkDateTime);
        }, $dateTimesArray);
        return in_array(true, $check);
    }
}

/* @function statusClass()  @version v1.0  @since 1.0.0 */
if (!function_exists('statusClass')) {
    function statusClass($string)
    {
        $status = [
            // Common statuses
            'pending' => 'warning',
            'waiting' => 'warning',
            'rejected' => 'danger',
            'active' => 'success',
            'success' => 'success',
            'approved' => 'success',
            'confirmed' => 'primary',
            'inactive' => 'danger',
            'canceled' => 'danger',
            'blocked' => 'danger',
            // Prescription
            'draft' => 'warning',
            'published' => 'success',
            // roles
            'user' => 'info',
            'admin' => 'danger',
            'master' => 'danger',
            'doctor' => 'primary',
            'patient' => 'success',
        ];
        return $status[$string] ?? 'primary';
    }
}

/*================================================*/
/*=========== Settings Related Data ==============*/
/*================================================*/

/* @function site_dependency()  @version v1.0  @since 1.0.0 */
if( ! function_exists('settings') ){
    function settings($name, $fallback = null)
    {
        if( is_array($name) && isset($name['key']) ){
            $setting = Setting::updateOrCreate([
                'option_name' => $name['key'] 
            ],[
                'option_value' => $name['value'] ?? null
            ]);
        }elseif(is_string($name)){
            // $setting = Setting::where( 'option_name', $name)->first();
            $setting = Setting::cachedSettings()->where( 'option_name', $name)->first();
        }else{
            return $fallback;
        }
        return filterValue($setting->option_value ?? $fallback);
    }
}
