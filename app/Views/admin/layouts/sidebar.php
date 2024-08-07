<ul class="navbar-nav sidebar sidebar-dark accordion bg-primary" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/admin">
        <img src="<?= base_url('img/logo-header.png') ?>" width="" height="45" alt="">

    </a>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="/admin">
            <i class="fas fa-home"></i>
            <span>Dashboard</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#finance" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-coins mr-2"></i>
            <span>Keuangan</span>
        </a>
        <div id="finance" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/admin/add-credit">
                    Tambah Uang Masuk
                </a>
                <a class="collapse-item" href="/admin/add-debet">
                    Tambah Uang Keluar
                </a>
                <a class="collapse-item" href="/admin/finance">
                    Laporan Keuangan
                </a>

            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#reservasi" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-bed"></i>
            <span>Reservasi</span>
        </a>
        <div id="reservasi" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/admin/reservation/add">
                    <i class="fas fa-plus"></i> Tambah Reservasi
                </a>
                <a class="collapse-item" href="/admin/reservation">
                    <i class="fas fa-bed"></i> Daftar Reservasi
                </a>
            </div>
        </div>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider">

    <?php
    $role = $_SESSION['role'];
    if ($role === 'super_admin') :
    ?>


        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-cogs"></i>
                <span>Kamar</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="/admin/tambah-kamar">
                        <i class="fas fa-bed"></i> Tambah Kamar
                    </a>
                    <a class="collapse-item" href="/admin/trouble-kamar">
                        <i class="fas fa-exclamation-triangle"></i> Troubel Kamar
                    </a>

                </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/admin/komplain">
                <i class="fas fa-comment-alt mr-2"></i> <!-- Menggunakan ikon komplain -->
                <span>Komplain Tamu</span>
            </a>
        </li>
        <!-- Divider -->
        <hr class="sidebar-divider">
        <!-- Heading -->
        <!-- <div class="sidebar-heading">
        Keuangan
    </div> -->

        <!-- Nav Item - Laporan -->
        <li class="nav-item">
            <a class="nav-link" href="/admin/report">
                <i class="fas fa-chart-bar"></i>
                <span>Laporan Bulanan</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/list-users">
                <i class="fas fa-chart-bar"></i>
                <span>Daftar Users</span></a>
        </li>


        <!-- <li class="nav-item">
        <a class="nav-link" href="/admin/kas-masuk">
            <i class="fas fa-money-bill-wave"></i>
            <span>Kas Masuk</span>
        </a>
    </li> -->


        <!-- <li class="nav-item">
        <a class="nav-link" href="/admin/finance">
            <i class="fas fa-coins mr-2"></i>
            <span>Keuangan</span></a>
    </li> -->



        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

    <?php endif; ?>
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>