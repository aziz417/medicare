<div id="navbar2" class="app-navbar vertical dark">
    <div class="navbar-wrap"><button class="no-style navbar-toggle navbar-close icofont-close-line d-lg-none"></button>
        <div class="app-logo">
            <div class="logo-wrap"><img src="{{ asset('assets/img/logo-white.svg') }}" alt="" width="147" height="33" class="logo-img"></div>
        </div>
        <div class="main-menu">
            <nav class="main-menu-wrap">
                <ul class="menu-ul">
                    <li class="menu-item"><span class="group-title">Medicine</span></li>
                    <li class="menu-item"><a class="item-link" href="{{ route('admin.home') }}"><span class="link-icon icofont-thermometer-alt"></span> <span class="link-text">Dashboard</span></a></li>
                    @if( $auth->isAdmin(false) )
                    <li class="menu-item"><a class="item-link" href="{{ route('admin.departments.index') }}"><span class="link-icon icofont-chart-flow-1"></span> <span class="link-text">Departments</span></a></li>
                    @endif
                    <li class="menu-item"><a class="item-link" href="{{ route('admin.doctors.index') }}"><span class="link-icon icofont-doctor"></span> <span class="link-text">Doctors</span></a></li>
                    <li class="menu-item"><a class="item-link" href="{{ route('admin.appointments.index') }}"><span class="link-icon icofont-stethoscope-alt"></span> <span class="link-text">Appointments</span></a></li>
                    <li class="menu-item"><a class="item-link" href="{{ route('admin.prescriptions.index') }}"><span class="link-icon icofont-prescription"></span> <span class="link-text">Prescriptions</span></a></li>
                    <li class="menu-item"><a class="item-link" href="{{ route('admin.patients.index') }}"><span class="link-icon icofont-paralysis-disability"></span> <span class="link-text">Patients</span></a></li>
                    <li class="menu-item"><a class="item-link" href="{{ route('admin.schedules.index') }}"><span class="link-icon icofont-clock-time"></span> <span class="link-text">Schedules</span></a></li>
                    <li class="menu-item"><a class="item-link" href="{{ route('admin.advices.index') }}"><span class="link-icon icofont-plus"></span> <span class="link-text">Advice</span></a></li>
                    @if($auth->isRole('doctor'))
                    <li class="menu-item"><a class="item-link" href="{{ route('admin.video.settings') }}"><span class="link-icon icofont-ui-video-chat"></span> <span class="link-text">Video Setting</span></a></li>
                    @endif
                    <li class="menu-item"><a class="item-link" href="{{ route('admin.prescriptions-templates.index') }}"><span class="link-icon icofont-file-alt"></span> <span class="link-text">Prescription Templates</span></a></li>
                    @if($auth->isRole('doctor') && $auth->is_desk_doctor == 1)
                        <li class="menu-item"><a class="item-link" href="{{ route('admin.transactions.index') }}"><span class="link-icon icofont-file-alt"></span> <span class="link-text">Payment</span></a></li>
                    @endif

                    @if( $auth->isAdmin(false) )
                    <li class="menu-item"><span class="group-title">Apps</span></li>

                    <li class="menu-item has-sub"><a class="item-link" href="index.html#"><span class="link-text">Payments</span> <span class="link-caret icofont-thin-right"></span></a>
                        <ul class="sub">
                            <li class="menu-item"><a class="item-link" href="{{ route('admin.transactions.index') }}"> <span class="link-text">Transactions</span></a></li>
                            <li class="menu-item"><a class="item-link" href="{{ route('admin.accounts') }}"> <span class="link-text">Account Balance</span></a></li>
                        </ul>
                    </li>

                    <li class="menu-item has-sub"><a class="item-link" href="index.html#"><span class="link-text">SMS & Email</span> <span class="link-caret icofont-thin-right"></span></a>
                        <ul class="sub">
                            <li class="menu-item"><a class="item-link" href="{{ route('admin.templates.index') }}"><span class="link-text">Templates</span></a></li>
                            <li class="menu-item"><a class="item-link" href="{{ route('admin.sender.sms') }}"><span class="link-text">SMS</span></a></li>
                            <li class="menu-item"><a class="item-link" href="{{ route('admin.sender.email') }}"><span class="link-text">Email</span></a></li>
                        </ul>
                    </li>
                    <li class="menu-item has-sub"><a class="item-link" href="index.html#"><span class="link-text">Others</span> <span class="link-caret icofont-thin-right"></span></a>
                        <ul class="sub">
                            <li class="menu-item"><a class="item-link" href="{{ route('admin.medicines.index') }}"><span class="link-text">Medicines</span></a></li>
                            <li class="menu-item"><a class="item-link" href="{{ route('admin.discounts.index') }}"><span class="link-text">Discounts</span></a></li>
                            <li class="menu-item"><a class="item-link" href="{{ route('admin.badges.index') }}"><span class="link-text">Badges</span></a></li>
                            <li class="menu-item"><a class="item-link" href="{{ route('admin.users.index') }}"><span class="link-text">Admin Users</span></a></li>
                        </ul>
                    </li>
                    @endif

                    @if( $auth->isRole('doctor') && $auth->is_desk_doctor == 1 )
                    <li class="menu-item"><span class="group-title">Apps</span></li>
                    <li class="menu-item has-sub"><a class="item-link" href="index.html#"><span class="link-text">Others</span> <span class="link-caret icofont-thin-right"></span></a>
                        <ul class="sub">
                            <li class="menu-item"><a class="item-link" href="{{ route('admin.medicines.index') }}"><span class="link-text">Medicines</span></a></li>
                            <li class="menu-item"><a class="item-link" href="{{ route('admin.discounts.index') }}"><span class="link-text">Discounts</span></a></li>
                            <li class="menu-item"><a class="item-link" href="{{ route('admin.badges.index') }}"><span class="link-text">Badges</span></a></li>
                        </ul>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>
        <div class="add-patient">
            {{-- <button class="btn btn-primary" data-toggle="modal" data-target="#add-patient"><span class="btn-icon icofont-plus mr-2"></span> Add Patient</button> --}}
        </div>
        @if( $auth->isSuperAdmin() )
        <div class="assistant-menu">
            <a class="link" href="{{ route('admin.settings') }}"><span class="link-icon icofont-ui-settings"></span>Settings </a>
        </div>
        @endif

        @includeIf('partials.navbar-skeleton')
    </div>
</div>
