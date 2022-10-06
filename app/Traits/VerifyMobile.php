<?php

namespace App\Traits;

use App\Models\Template;
use App\Contracts\MustVerifyMobile;
/**
 * VerifyMobile Trait
 * 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
trait VerifyMobile// implements MustVerifyMobile where use this trait
{
    /**
     * Determine if the user has verified their mobile address.
     *
     * @return bool
     */
    public function hasVerifiedMobile()
    {
        return ! is_null($this->mobile_verified_at);
    }

    /**
     * Mark the given user's mobile as verified.
     *
     * @return bool
     */
    public function markMobileAsVerified()
    {
        return $this->forceFill([
            'mobile_verified_at' => $this->freshTimestamp(),
            'otp' => null
        ])->save();
    }

    /**
     * Send the mobile verification notification.
     *
     * @return void
     */
    public function sendMobileVerificationNotification()
    {
        $otp = $this->saveOtpForVerification();
        $name = config('app.name');
        $template = Template::getTemplate('MOBILE_VERIFICATION');
        $content = optional($template)->compiled([
            '[[OTP]]' => $otp
        ]);
        $response = app('SMS')
            ->to($this->getMobileForVerification())
            ->content($content ?? "Your mobile verification OTP is: {$otp}")
            ->send();
        return $response;
    }

    /**
     * Get the mobile number that should be used for verification.
     *
     * @return string
     */
    public function getMobileForVerification()
    {
        return $this->mobile;
    }

    /**
     * Set a OTP to the user
     *
     * @return string
     */
    public function saveOtpForVerification()
    {
        $otp = rand(1111, 9999);
        $this->forceFill([
            'otp' => $otp,
        ])->save();
        return $otp;
    }
    
    /**
     * Get the saved OTP of the user
     *
     * @return string
     */
    public function getSavedOtp()
    {
        return $this->otp;
    }
}
