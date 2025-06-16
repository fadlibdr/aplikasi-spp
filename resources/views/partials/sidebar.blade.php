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

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="/dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    @can('view pembayaran')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('pembayaran.index') }}">
                <i class="fas fa-credit-card"></i>
                <span>Pembayaran</span>
            </a>
        </li>
    @endcan

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Data Sekolah
    </div>

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('profil.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Profil Sekolah</span></a>
    </li>

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('tahun-ajaran.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Tahun Akademik</span></a>
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
                <a class="collapse-item" href="{{ route('siswa.index') }}">Biodata Siswa</a>
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
                <a class="collapse-item" href="{{ route('jenis.index') }}">Jenis Iuran</a>
                <a class="collapse-item" href="{{ route('iuran.index') }}">Iuran</a>
                <a class="collapse-item" href="{{ route('keuangan.index') }}">Keuangan</a>
                <a class="collapse-item" href="{{ route('riwayat.index') }}">Riwayat Pembayaran</a>
                <a class="collapse-item" href="{{ route('cek-pembayaran.index') }}">Tagihan</a>
                <a class="collapse-item" href="{{ route('jurnal-umum.index') }}">Jurnal Umum</a>
            </div>
        </div>
    </li>

    @role('admin')
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Laporan dan Pengaturan
    </div>

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse" aria-expanded="true"
            aria-controls="collapse">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Laporan</span>
        </a>
        <div id="collapse" class="collapse" aria-labelledby="heading" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Laporan</h6>
                <a class="collapse-item" href="{{ route('laporan.index') }}">Laporan</a>
            </div>
        </div>
    </li>


    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Settings</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Settings</h6>
                <a class="collapse-item" href="{{ route('users.index') }}">User Management</a>
                <a class="collapse-item" href="{{ route('settings.index') }}">Application Settings</a>
                <a class="collapse-item" href="{{ route('audit.index') }}">Audit Logs</a>
            </div>
        </div>
    </li>

    @endrole

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>