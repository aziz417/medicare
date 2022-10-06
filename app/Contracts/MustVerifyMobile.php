<?php

namespace App\Contracts;
/**
 * Must Verify Mobile
 * 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
interface MustVerifyMobile
{
    /**
     * Determine if the user has verified their mobile address.
     *
     * @return bool
     */
    public function hasVerifiedMobile();

    /**
     * Mark the given user's mobile as verified.
     *
     * @return bool
     */
    public function markMobileAsVerified();

    /**
     * Send the mobile verification notification.
     *
     * @return void
     */
    public function sendMobileVerificationNotification();

    /**
     * Get the mobile address that should be used for verification.
     *
     * @return string
     */
    public function getMobileForVerification();

    /**
     * Set the mobile otp that was saved for verification
     *
     * @return string
     */
    public function saveOtpForVerification();

    /**
     * Get the mobile otp that was saved for verification
     *
     * @return string
     */
    public function getSavedOtp();
}
