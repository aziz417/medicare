<?php 

return [
    [
        'name' => "Mobile Verification", 
        'subject' => "Verify your mobile number", 
        'key' => "MOBILE_VERIFICATION", 
        'type' => "sms", 
        'content' => "Your mobile verification OTP is: [[OTP]]\nFor [[APP_NAME]]", 
        'action' => null, 
        'after' => null, 
        'removable' => false
    ],
    [
        'name' => "Upcoming Appointment Reminder", 
        'subject' => "Appointment Reminder", 
        'key' => "NOTIFY_UPCOMING_APPOINTMENT_USER", 
        'type' => "sms", 
        'content' => "Hello [[USER_NAME]], this is a reminder that you have an appointment at [[APPOINTMENT_DATETIME]] on [[APP_NAME]]",
        'action' => null, 
        'after' => null, 
        'removable' => false
    ],
];