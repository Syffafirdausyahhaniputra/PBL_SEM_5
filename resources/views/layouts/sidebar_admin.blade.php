<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{ url('/welcome') }}" class="nav-link {{ $activeMenu == 'dashboard' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/riwayat') }}" class="nav-link {{ $activeMenu == 'riwayat' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-history"></i>
                    <p>Riwayat</p>
                </a>
            </li>
            <!-- Manage Dropdown -->
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="nav-icon far fa-user"></i>
                    <p>
                        Manage
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/manage/user') }}" class="nav-link {{ $activeMenu == 'manage_user' ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Manage User</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/manage/jenis_pengguna') }}" class="nav-link {{ $activeMenu == 'manage_jenis_pengguna' ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Manage Jenis Pengguna</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/manage/vendor') }}" class="nav-link {{ $activeMenu == 'manage_vendor' ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Manage Vendor</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/manage/bidang') }}" class="nav-link {{ $activeMenu == 'manage_bidang' ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Manage Bidang</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/manage/mata_kuliah') }}" class="nav-link {{ $activeMenu == 'manage_mata_kuliah' ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Manage Mata Kuliah</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/manage/jenis_sertifikasi') }}" class="nav-link {{ $activeMenu == 'manage_jenis_sertifikasi' ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Manage Jenis Sertifikasi dan Pelatihan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/manage/sertifikasi') }}" class="nav-link {{ $activeMenu == 'manage_sertifikasi' ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Manage Sertifikasi dan Pelatihan</p>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Profile -->
            <li class="nav-item">
                <a href="{{ url('/profile') }}" class="nav-link {{ $activeMenu == 'profile' ? 'active' : '' }}">
                    <i class="nav-icon far fa-user"></i>
                    <p>Profile</p>
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
