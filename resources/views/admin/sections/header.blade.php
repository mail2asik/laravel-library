<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="{{ secure_url('/') }}" class="logo">
       <!-- <img src="{{ secure_asset('img/logo.png') }}" height="50" /> -->
        {{ config('app.site_name') }}
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="{{ secure_asset("/img/default_". $logged_in_user['gender'] .".jpg") }}" class="user-image" alt="User Image"/>
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{ ucfirst($logged_in_user['first_name']) .' '. ucfirst($logged_in_user['last_name']) }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="{{ secure_asset("/img/default_". $logged_in_user['gender'] .".jpg") }}" class="img-circle" alt="User Image" />
                            <p>
                                {{ucfirst($logged_in_user['first_name']) .' '. ucfirst($logged_in_user['last_name']) }}
                                <small>Member since {{ date('M. Y', strtotime($logged_in_user['created_at'])) }}</small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-right">
                                <a href="{{ secure_url('logout')  }}" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>