<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{ url('/welcome') }}" class="nav-link {{ $activeMenu == 'dashboard' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-home"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/riwayat') }}" class="nav-link {{ $activeMenu == 'riwayat' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-clock"></i>
                    <p>Riwayat</p>
                </a>
            </li>
            <!-- Manage Dropdown -->
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-users-cog"></i>
                    <p>
                        Manage
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/user') }}" class="nav-link {{ $activeMenu == 'user' ? 'active' : '' }}">
                            <i class="fas fa-users nav-icon"></i>
                            <p>Manage User</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/role') }}" class="nav-link {{ $activeMenu == 'role' ? 'active' : '' }}">
                            <i class="fas fa-briefcase nav-icon"></i>
                            <p>Manage Jabatan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/vendor') }}" class="nav-link {{ $activeMenu == 'vendor' ? 'active' : '' }}">
                            <i class="fas fa-chalkboard-teacher nav-icon"></i>
                            <p>Manage Vendor</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/bidang') }}" class="nav-link {{ $activeMenu == 'bidang' ? 'active' : '' }}">
                            <i class="fas fa-sitemap nav-icon"></i>
                            <p>Manage Bidang</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/mata_kuliah') }}" class="nav-link {{ $activeMenu == 'mata_kuliah' ? 'active' : '' }}">
                            <i class="fas fa-book nav-icon"></i>
                            <p>Manage Mata Kuliah</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/jenis_sertifikasi') }}" class="nav-link {{ $activeMenu == 'jenis_sertifikasi' ? 'active' : '' }}">
                            <i class="fas fa-certificate nav-icon"></i>
                            <p>Manage Jenis Sertifikasi dan Pelatihan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/sertifikasi') }}" class="nav-link {{ $activeMenu == 'sertifikasi' ? 'active' : '' }}">
                            <i class="fas fa-award nav-icon"></i>
                            <p>Manage Sertifikasi dan Pelatihan</p>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Profile -->
            <li class="nav-item">
                <a href="{{ url('/profile') }}" class="nav-link {{ $activeMenu == 'profile' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-circle"></i>
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
