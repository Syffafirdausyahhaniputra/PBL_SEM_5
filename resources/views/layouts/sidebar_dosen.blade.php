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
                <a href="{{ url('/notifikasidosen') }}" class="nav-link {{ $activeMenu == 'notifikasidosen' ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-history"></i>
                    <p>Notifikasi</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/profileDosen') }}" class="nav-link {{ $activeMenu == 'profileDosen' ? 'active' : '' }} ">
                    <i class="nav-icon far fa-user"></i>
                    <p>Profile</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/sertifikasi/dosen') }}" class="nav-link {{ $activeMenu == 'sertifikasi_dosen' ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-folder"></i>
                    <p>Data Sertifikasi</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/pelatihan/dosen') }}" class="nav-link {{ $activeMenu == 'pelatihan_dosen' ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-file"></i>
                    <p>Data Pelatihan</p>
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
