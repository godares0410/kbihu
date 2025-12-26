<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <?php 
                $user = auth();
                $fotoUrl = !empty($user['foto']) 
                    ? asset('image/users/' . $user['foto']) 
                    : asset('image/icon.png');
                ?>
                <img src="<?= $fotoUrl ?>" class="img-circle" alt="User Image" onerror="this.src='<?= asset('image/icon.png') ?>'">
            </div>
            <div class="pull-left info">
                <p><?= $user['name'] ?? 'Admin' ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li class="<?= Request::is('home') ? 'active' : '' ?>">
                <a href="<?= url('/home') ?>">
                    <i class="fa fa-tachometer"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="<?= Request::is('data-peserta*') || Request::is('peserta') ? 'active' : '' ?>">
                <a href="<?= url('/data-peserta') ?>">
                    <i class="fa fa-users"></i> <span>Data Peserta</span>
                </a>
            </li>
            <li class="<?= Request::is('data-cetak') ? 'active' : '' ?>">
                <a href="<?= url('/data-cetak') ?>">
                    <i class="fa fa-print"></i> <span>Cetak Kartu</span>
                </a>
            </li>
            <li class="<?= Request::is('data-scan') ? 'active' : '' ?>">
                <a href="<?= url('/data-scan') ?>">
                    <i class="fa fa-qrcode"></i> <span>Data Scan</span>
                </a>
            </li>
            <li class="<?= Request::is('data-export') ? 'active' : '' ?>">
                <a href="<?= url('/data-export') ?>">
                    <i class="fa fa-file-excel-o"></i> <span>Data Export</span>
                </a>
            </li>
            <li class="<?= Request::is('admin-users') ? 'active' : '' ?>">
                <a href="<?= url('/admin-users') ?>">
                    <i class="fa fa-user-secret"></i> <span>User Admin</span>
                </a>
            </li>
        </ul>
    </section>
</aside>
