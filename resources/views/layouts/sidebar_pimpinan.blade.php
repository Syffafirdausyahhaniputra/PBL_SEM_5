<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{ url('/welcome') }}" class="nav-link {{ $activeMenu == 'dashboard' ? 'active' : '' }} ">
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
                <a href="{{ url('/kompetensi') }}" class="nav-link {{ $activeMenu == 'profile' ? 'active' : '' }} ">
                {{-- <a href="{{ url('/kompetensi') }}" class="nav-link"> --}}
                    <i class="nav-icon far fa-user"></i>
                    <p>Kompetensi Prodi</p>
                </a>
            </li>
            <br>
            <!-- Logout Button -->
            <li class="nav-item">
                <a href="{{ url('/logout') }}" class="nav-link">
                    <i class="nav-icon fas fa-sign-out-alt"></i> 
                    <p>Logout</p>
                </a>
            </li>
        </ul>
    </nav>
</div>
