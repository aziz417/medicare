<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Http\View\Composers\ProfileComposer;

class CustomServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind('path.public', function() {
        //     return realpath(base_path("../public_html"));
        // });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Factory $factory)
    {
        $this->overrideSettings();
        $this->registerMacro();
        
        $factory->composer('*', ProfileComposer::class);

        $assets_disk = public_path('uploads/');
        if( ! file_exists( $assets_disk ) && ! is_dir( $assets_disk ) ){
            try { mkdir($assets_disk, 0777); } catch (\Exception $e) {}
        }
    }

    public function registerMacro()
    {

        Validator::extend('password_check', function ($attribute, $value, $parameters, $validator) {
            $hashedValue = $parameters[0] ?? "";
            return Hash::check($value, $hashedValue);
        }, __("Wrong password given!")); // Uses: "password_check:{$user->password}"
        Validator::extend('mobile', function ($attribute, $value, $parameters, $validator) {
            return preg_match("/^(?:\\+880|880|0)?(1[3-9]\d{8})$/", $value);
        }, __("Invalid mobile number given!")); // Uses: "mobile"

        Builder::macro('whereLike', function ($attributes, string $searchTerm) {
            $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->when(
                        Str::contains($attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerm) {
                            [$relationName, $relationAttribute] = explode('.', $attribute);

                            $query->orWhereHas($relationName, function (Builder $query) use ($relationAttribute, $searchTerm) {
                                $query->where($relationAttribute, 'LIKE', $searchTerm);
                            });
                        },
                        function (Builder $query) use ($attribute, $searchTerm) {
                            $query->orWhere($attribute, 'LIKE', $searchTerm);
                        }
                    );
                }
            });
            return $this;
        });
    }

    public function overrideSettings()
    {
        if (Schema::hasTable('settings')) {
            Config::set([
                'app.name' => settings('app_name', config('app.name')),
                
                'mail.mailers.smtp.host' => settings('email_smtp_host', env('MAIL_HOST')),
                'mail.mailers.smtp.port' => settings('email_smtp_port', env('MAIL_PORT')),
                'mail.mailers.smtp.username' => settings('email_smtp_user', env('MAIL_USERNAME')),
                'mail.mailers.smtp.password' => settings('email_smtp_password', env('MAIL_PASSWORD')),
                'mail.mailers.smtp.encryption' => settings('email_smtp_encription', env('MAIL_ENCRYPTION')),
                'mail.from.address' => settings('email_from_address', env('MAIL_FROM_ADDRESS')),
                'mail.from.name' => settings('email_from_name', env('MAIL_FROM_NAME')),

                'services.payment.paypal.client_id' => settings('payment_paypal_client_id', env('PAYPAL_CLIENT_ID')),
                'services.payment.paypal.client_secret' => settings('payment_paypal_client_secret', env('PAYPAL_CLIENT_SECRET')),
                'services.payment.paypal.sandbox' => settings('payment_paypal_sandbox', env('PAYPAL_SANDBOX'))=='on',

                'services.payment.portwallet.app_key' => settings('payment_portwallet_app_key', env('PORTWALLET_API_KEY')),
                'services.payment.portwallet.app_secret' => settings('payment_portwallet_app_secret', env('PORTWALLET_API_SECRET')),
                'services.payment.portwallet.sandbox' => settings('payment_portwallet_sandbox', env('PORTWALLET_SANDBOX'))=='true',
            ]);
        }
    }
}
