<div class="header">
    <div class="container">
        <div class="logo"> <a href="{{ secure_url('/') }}"><img src="{{  secure_asset('/img/logo.png') }}" alt="{{ config('app.site_name') }}" width="130"></a> </div>
        <div class="menu"> <a class="toggleMenu" href="#"><img src="{{  secure_asset('img/nav_icon.png') }}" alt="" /> </a>
            <ul class="nav" id="nav">
                <li class="{{ Request::is( '/') ? 'current' : '' }}"><a href="{{ secure_url('/') }}">Home</a></li>
                <li class="{{ Request::is( 'about-us') ? 'current' : '' }}"><a href="{{ secure_url('about-us') }}">About Us</a></li>

                <li><a href="javascript:;">Services</a></li>
                <li><a href="javascript:;">Winners</a></li>
                <li><a href="javascript:;">Jobs</a></li>
                @if(!empty($logged_in_user))
                    <li class="bgGrey">
                        @if($logged_in_user['role']['slug'] != 'customer')
                            <a href="{{ secure_url('admin/dashboard') }}">Dashboard</a>
                        @else
                            <a href="{{ secure_url('dashboard') }}">Dashboard</a>
                        @endif
                    </li>
                    <li><a href="{{ secure_url('logout') }}">Logout</a></li>
                @else
                    <li class="{{ Request::is( 'login') || Request::is( 'forgot') ? 'current' : '' }}">
                        <a href="{{ secure_url('login') }}">Login</a>
                    </li>
                @endif
                <div class="clear"></div>
            </ul>
        </div>
        <div class="clearfix"> </div>
        <div class="menu fontWeightBold">
            <span>
            @if(!empty($logged_in_user))
            Welcome {{$logged_in_user['first_name'] .' '. $logged_in_user['last_name']}}!
            @endif
            &nbsp;
            </span>
        </div>
    </div>
</div>