<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dashboard">

        <!-- Sidebar - Logo -->
        <div class="sidebar-brand-icon">
            {{-- @if($profil && $profil->logo)
            <img src="{{ asset($profil->logo) }}" style="height: 40px;">
            @else--}}
            <i class="fas fa-school"></i>
            {{-- @endif--}}
        </div>

        <div class="sidebar-brand-text mx-3">{{ $app_name }}</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    @role('admin')
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-users-cog"></i>
            <span>User Management</span>
        </a>
    </li>
    @endrole

    @can('view pembayaran')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('pembayaran.index') }}">
                <i class="fas fa-credit-card"></i>
                <span>Pembayaran</span>
            </a>
        </li>
    @endcan


    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="/dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Data Sekolah
    </div>

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Profil Sekolah</span></a>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Manajemen Kelas</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manajemen Kelas</h6>
                <a class="collapse-item" href="{{ route(name: 'tahun-ajaran.index') }}">Tahun Ajaran</a>
                <a class="collapse-item" href="{{ route('kelas.index') }}">Kelas</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Components Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseComponents"
            aria-expanded="true" aria-controls="collapseComponents">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Manajemen Siswa</span>
        </a>
        <div id="collapseComponents" class="collapse" aria-labelledby="headingComponents"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Data Siswa</h6>
                <a class="collapse-item" href="{{ route(name: 'siswa.index') }}">Biodata Siswa</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Manajemen Keuangan</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Data Siswa</h6>
                <a class="collapse-item" href="{{ route(name: 'jenis.index') }}">Jenis Iuran</a>
                <a class="collapse-item" href="{{ route(name: 'iuran.index') }}">Iuran</a>
                <a class="collapse-item" href="{{ route(name: 'keuangan.index') }}">Keuangan</a>
                <a class="collapse-item" href="{{ route(name: 'riwayat.index') }}">Riwayat Pembayaran</a>
                <a class="collapse-item" href="{{ route(name: 'cek-pembayaran.index') }}">Cek Pembayaran</a>
                <a class="collapse-item" href="{{ route(name: 'jurnal-umum.index') }}">Jurnal Umum</a>
                <a class="collapse-item" href="{{ route(name: 'users.index') }}">User Management</a>
                <a class="collapse-item" href="{{ route(name: 'settings.index') }}">Application Settings</a>


            </div>
        </div>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Laporan dan Pengaturan
    </div>

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseComponent"
            aria-expanded="true" aria-controls="collapseComponent">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Laporan</span>

            <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Settings</h6>
                    <a class="collapse-item" href="{{ route(name: 'users.index') }}">User Management</a>
                    <a class="collapse-item" href="{{ route(name: 'settings.index') }}">Application Settings</a>



                </div>
            </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>