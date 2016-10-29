<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ secure_asset("/img/default_". $logged_in_user['gender'] .".jpg") }}" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p>{{ ucfirst($logged_in_user['first_name']) .' '. ucfirst($logged_in_user['last_name']) }}</p>
            </div>
        </div>


        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="{{ Request::is( 'admin/dashboard') ? 'active' : '' }}"><a href="{{ secure_url('admin/dashboard') }}"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>

            <li class="header">Members</li>
            <li class="{{ Request::is( 'admin/users/create') ? 'active' : '' }}"><a href="{{ secure_url('admin/users/create') }}"><i class="fa fa-user-plus"></i><span>Add</span></a></li>
            <li class="{{ Request::is( 'admin/users') ? 'active' : '' }}"><a href="{{ session()->has('page.users')? session('page.users') : secure_url('admin/users') }}"><i class="fa fa-users"></i><span>List</span></a></li>

            <li class="header">Accounts</li>
            <li class="{{ Request::is( 'admin/change-profile') ? 'active' : '' }}"><a href="{{ secure_url('admin/change-profile') }}"><i class="fa fa-edit"></i><span>My Profile</span></a></li>
            <li class="{{ Request::is( 'admin/change-password') ? 'active' : '' }}"><a href="{{ secure_url('admin/change-password') }}"><i class="fa fa-key"></i><span>Change Password</span></a></li>
            <li><a href="{{ secure_url('logout') }}"><i class="fa fa-lock"></i><span>Logout</span></a></li>

        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>