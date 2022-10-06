<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Appointment' => 'App\Policies\AppointmentPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // Check the user is super admin or not
        Gate::before(function ($user, $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });
        
        $this->registerAppointmentGates();
        $this->registerPrescriptionGates();
        $this->registerUsersGates();
        $this->registerTransactionsGates();
    }

    public function registerAppointmentGates()
    {
        // Check the user can delete the appointment
        Gate::define('edit-appointment', 'App\Policies\AppointmentPolicy@update');
        Gate::define('delete-appointment', 'App\Policies\AppointmentPolicy@delete');
        Gate::define('start-appointment-call', 'App\Policies\AppointmentPolicy@startCall');
    }

    public function registerPrescriptionGates()
    {
        // Check the user can delete the appointment
        Gate::define('delete-prescription', 'App\Policies\PrescriptionPolicy@delete');
    }

    public function registerUsersGates()
    {
        // Check the authenticated user can delete the users
        Gate::define('delete-user', 'App\Policies\UserPolicy@delete');
        // Check the authenticated user can edit the doctor
        Gate::define('edit-doctor', 'App\Policies\UserPolicy@updateDoctor');
        // Check the authenticated user can delete the doctors
        Gate::define('delete-doctor', 'App\Policies\UserPolicy@deleteDoctor');
    }

    public function registerTransactionsGates()
    {
        // Check the authenticated user can access the transaction
        Gate::define('access-transaction', 'App\Policies\TransactionPolicy@view');
        Gate::define('update-transaction', 'App\Policies\TransactionPolicy@update');
        Gate::define('delete-transaction', 'App\Policies\TransactionPolicy@delete');
    }
}
