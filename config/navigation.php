<?php 
return [

    // Admin Access Routes
    "admin" => [
        [
            'title' => "Dashboard",
            'route' => 'admin.home',
            'icon' => "icofont-thermometer-alt"
        ],
        [
            'title' => "Appointments",
            'route' => 'admin.appointments.index',
            'icon' => 'icofont-stethoscope-alt'
        ],
        [
            'title' => "Doctors",
            'route' => 'admin.doctors.index',
            'icon' => 'icofont-doctor'
        ],
    ],

    // User Access Routes
    "users" => [
        [
            'title' => "Dashboard",
            'route' => 'admin.home'
        ],
    ],
];