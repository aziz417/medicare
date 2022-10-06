<div id="navbar1" class="app-navbar horizontal">
    <div class="navbar-wrap">
        <button class="no-style navbar-toggle navbar-open d-lg-none"><span></span><span></span><span></span></button>
        <div class="app-logo">
            <div class="logo-wrap"><img src="{{ asset('assets/img/logo.svg') }}" alt="" width="147" height="33" class="logo-img"></div>
        </div>
        <form class="app-search d-none d-md-block">
            <div class="form-group typeahead__container with-suffix-icon mb-0">
                <div class="typeahead__field">
                    <div class="typeahead__query">
                        <input class="form-control topbar-search" type="search" placeholder="Type page's title  to quick navigate" autocomplete="off">
                        <div class="suffix-icon icofont-search"></div>
                    </div>
                </div>
            </div>
        </form>
        <div class="app-actions">

            <x-notifications limit="20"/>

            <div class="dropdown item">
                <button class="no-style dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-offset="0, 10">
                    <span class="d-flex align-items-center">
                        <img src="{{ asset($auth->avatar()) }}" alt="" width="40" height="40" class="rounded-500 mr-1"> <i class="icofont-simple-down"></i>
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-w-180">
                    <h4 class="m-0 border-bottom dropdown-header">
                        <strong title="Role: {{ ucfirst($auth->role) }}">{{ $auth->name }}</strong> <br>
                        <small><i title="ID: {{ $auth->id }}">{{ $auth->email }}</i></small>
                    </h4>
                    
                    <ul class="list">
                        <li><a href="{{ route('common.profile') }}" class="align-items-center"><span class="icon icofont-ui-user"></span> User profile</a></li>
                        @if( $auth->isRole(['patient', 'user']) )
                        <li><a href="{{ route('user.sub-members.index') }}" class="align-items-center"><span class="icon icofont-users"></span> Sub-Members</a></li>
                        @endif
                        @if( $auth->isRole('doctor') )
                        <li><a href="{{ route('common.profile.wallet') }}" class="align-items-center"><span class="icon icofont-wallet"></span> My Wallet</a></li>
                        @endif
                        <li><a href="{{ route('common.profile.edit') }}" class="align-items-center"><span class="icon icofont-ui-home"></span> Edit account</a></li>
                        <li><a href="{{ route('logout') }}" class="align-items-center" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="icon icofont-logout"></span> Log Out</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="navbar-skeleton horizontal">
            <div class="left-part d-flex align-items-center"><span class="navbar-button bg animated-bg d-lg-none"></span> <span class="sk-logo bg animated-bg d-none d-lg-block"></span> <span class="search d-none d-md-block bg animated-bg"></span></div>
            <div class="right-part d-flex align-items-center">
                <div class="icon-box"><span class="icon bg animated-bg"></span> <span class="badge"></span></div><span class="avatar bg animated-bg"></span>
            </div>
        </div>
    </div>
</div>