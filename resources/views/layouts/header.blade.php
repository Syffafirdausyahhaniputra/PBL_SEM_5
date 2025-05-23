<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- User Profile with Avatar -->
        <li class="nav-item">
            <a href="{{ url('/profile') }}" class="nav-link {{ $activeMenu == 'profile' ? 'active' : '' }}">
                <!-- Display user's name -->
                {{ Auth::user()->nama }} <span class="caret"></span>
                <!-- Display user's avatar -->
                <img src="{{ auth()->user()->avatar ? asset('avatars/' . auth()->user()->avatar) : asset('avatars/user.jpg') }}" class="rounded-circle" width="30" height="30" alt="User Avatar">
            </a>
        </li>
    </ul>
</nav>
