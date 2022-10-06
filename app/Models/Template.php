<?php

namespace App\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Template extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'subject', 'key', 'type', 'content', 'action', 'after', 'removable', 'hidden'];
    protected $casts = [
        'action' => Json::class
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('hidden', function (Builder $builder) {
            $builder->where('hidden', false);
        });
    }

    public function fromArray($data)
    {
        // $this->forceFill($data);
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
        return $this;
    }

    public static function getTemplate($key)
    {
        return self::where('key', $key)->first();
    }

    public static function generateKey($text)
    {
        // MOBILE_VERIFICATION
        return strtoupper(str()->slug($text, '_'));
    }

    public function compiled($additional = [], $user = null)
    {
        return $this->replaceShortCode($this->content, $additional, $user);
    }
    public function compileCustomContent($content, $additional = [], $user = null)
    {
        return $this->replaceShortCode($content, $additional, $user);
    }

    public function replaceShortCode($text, $additional = [], $user = null)
    {
        $user = $user ?? auth()->user();
        $shortcodes = [
            "[[APP_NAME]]" => settings('app_name', config('app.name')),
            "[[APP_EMAIL]]" => settings('app_email'),
            "[[APP_URL]]" => url('/'),
            "[[LOGIN_URL]]" => route('login'),
            "[[USER_NAME]]" => $user->name ?? '',
            "[[USER_EMAIL]]" => $user->email ?? '',
            "[[USER_MOBILE]]" => $user->mobile ?? '',
        ];
        $text = str_replace(array_keys($shortcodes), array_values($shortcodes), $text);
        $text = str_replace(array_keys($additional), array_values($additional), $text);
        
        return $text;
    }
}
