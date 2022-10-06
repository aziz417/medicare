<div id="navbar2" class="app-navbar horizontal horizontal-vertical">
    <div class="navbar-wrap"><button class="no-style navbar-toggle navbar-close icofont-close-line d-lg-none"></button>
        <div class="app-logo">
            <div class="logo-wrap"><img src="{{ asset('assets/img/logo.svg') }}" alt="" width="147" height="33" class="logo-img"></div>
        </div>
        <div class="main-menu">
            <nav class="main-menu-wrap">
                <ul class="menu-ul">
                    <li class="menu-item"><a class="item-link" href="{{ route('user.home') }}"><span class="link-text">Dashboard</span></a></li>
                    <li class="menu-item has-sub"><a class="item-link" href="javascript:void(0)"><span class="link-text">Appointments</span> <span class="link-caret icofont-thin-right"></span></a>
                        <ul class="sub">
                            <li class="menu-item"><a class="item-link" href="{{ route('user.appointments.create') }}"><span class="link-text">Book Appointments</span></a></li>
                            <li class="menu-item"><a class="item-link" href="{{ route('user.appointments.index') }}"><span class="link-text">List Appointments</span></a></li>
                        </ul>
                    </li>
                    <li class="menu-item"><a class="item-link" href="{{ route('user.prescriptions.index') }}"><span class="link-text">Prescriptions</span></a></li>
                    <li class="menu-item"><a class="item-link" href="{{ route('user.doctors.index') }}"><span class="link-text">Doctors</span></a></li>
                    <li class="menu-item"><a class="item-link" href="{{ route('user.history.index') }}"><span class="link-text" title="Health History">History</span></a></li>
                    <li class="menu-item"><a class="item-link" href="{{ route('user.transactions.index') }}"><span class="link-text">Transactions</span></a></li>
                </ul>
            </nav>
        </div>
        
        @includeIf('partials.navbar-skeleton')
    </div>
</div>