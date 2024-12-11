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
                <a href="{{ url('/notifikasi') }}" class="nav-link {{ $activeMenu == 'notifikasi' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-clock"></i>
                    <p>Notifikasi</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/validasiAdmin') }}" class="nav-link {{ $activeMenu == 'validasiAdmin' ? 'active' : '' }} ">
                    <i class="nav-icon fas fa-check"></i>
                    <p>Validasi</p>
                </a>
            </li>
            <!-- Manage Dropdown -->
            <li class="nav-item has-treeview {{ in_array($activeMenu, ['user', 'role', 'vendor', 'bidang', 'matkul', 'prodi', 'kompetensi_prodi', 'jenis', 'level', 'sertifikasi', 'pelatihan']) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ in_array($activeMenu, ['user', 'role', 'vendor', 'bidang', 'matkul', 'prodi', 'kompetensi_prodi', 'jenis', 'level', 'sertifikasi', 'pelatihan']) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users-cog"></i>
                    <p>
                        Manage
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview" style="padding-left: 15px; font-size: 14px;">
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
                        <a href="{{ url('/matkul') }}" class="nav-link {{ $activeMenu == 'matkul' ? 'active' : '' }}">
                            <i class="fas fa-book nav-icon"></i>
                            <p>Manage Mata Kuliah</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/prodi') }}" class="nav-link {{ $activeMenu == 'prodi' ? 'active' : '' }}">
                            <i class="fas fa-book nav-icon"></i>
                            <p>Manage Prodi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/kompetensi_prodi') }}" class="nav-link {{ $activeMenu == 'kompetensi_prodi' ? 'active' : '' }}">
                            <i class="fas fa-graduation-cap nav-icon"></i>
                            <p>Manage Kompetensi Prodi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/jenis') }}" class="nav-link {{ $activeMenu == 'jenis' ? 'active' : '' }}">
                            <i class="fas fa-medal nav-icon"></i>
                            <p>Manage Jenis Sertifikasi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/level') }}" class="nav-link {{ $activeMenu == 'level' ? 'active' : '' }}">
                            <i class="fas fa-layer-group nav-icon"></i>
                            <p>Manage Level Pelatihan</p>
                        </a>
                    </li>                    
                    <li class="nav-item">
                        <a href="{{ url('/sertifikasi') }}" class="nav-link {{ $activeMenu == 'sertifikasi' ? 'active' : '' }}">
                            <i class="fas fa-award nav-icon"></i>
                            <p>Manage Sertifikasi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/pelatihan') }}" class="nav-link {{ $activeMenu == 'pelatihan' ? 'active' : '' }}">
                            <i class="fas fa-award nav-icon"></i>
                            <p>Manage Pelatihan</p>
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
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <p>Logout</p>
                </a>
            </li>
        </ul>
    </nav>
</div>