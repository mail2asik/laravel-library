<div class="col-md-4 blog_sidebar">
    <ul class="sidebar">
        <h3>Menu</h3>
        <li><a href="{{ secure_url('dashboard') }}" class="{{ Request::is( 'dashboard') ? 'current' : '' }}">Dashboard</a></li>
        <li>
            <a href="{{ session()->has('page.user_books')? session('page.user_books') : secure_url('books') }}" class="{{ Request::is( 'books') ? 'current' : '' }}">
                My Books
            </a>
        </li>
    </ul>
    <ul class="sidebar">
        <h3>My Account</h3>
        <li><a href="{{ secure_url('change-password') }}" class="{{ Request::is( 'change-password') ? 'current' : '' }}">Change Password</a></li>
        <li><a href="{{ secure_url('logout') }}">Logout</a></li>
    </ul>
</div>