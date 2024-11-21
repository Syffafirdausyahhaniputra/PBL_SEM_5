<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{ url('/welcome2') }}" class="nav-link {{ $activeMenu == 'dashboard' ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/notifikasi') }}" class="nav-link {{ $activeMenu == 'notifikasi' ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-history"></i>
                    <p>Notifikasi</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/profile') }}" class="nav-link {{ $activeMenu == 'profile' ? 'active' : '' }} ">
                    <i class="nav-icon far fa-user"></i>
                    <p>Profile</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/sertifikasi') }}" class="nav-link {{ $activeMenu == 'sertifikasi' ? 'active' : '' }} ">
                    <i class="nav-icon far fa-user"></i>
                    <p>Data Sertifikasi</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/pelatihan') }}" class="nav-link {{ $activeMenu == 'pelatihan' ? 'active' : '' }} ">
                    <i class="nav-icon far fa-user"></i>
                    <p>Data Pelatihan</p>
                </a>
            </li>
            <br>
            <!-- Logout Button -->
            <li class="nav-item">
                <a href="{{ url('/logout') }}" class="nav-link">
                    <button class="btn btn-danger btn-block">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </a>
            </li>
        </ul>
    </nav>
</div>
