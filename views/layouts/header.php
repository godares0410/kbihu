<header class="main-header">
    <a href="<?= url('/home') ?>" class="logo">
        <span class="logo-mini"><b>K</b>App</span>
        <span class="logo-lg"><b>Admin</b>KBIHU</span>
    </a>

    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?php 
                        $user = auth();
                        $fotoUrl = !empty($user['foto']) 
                            ? asset('image/users/' . $user['foto']) 
                            : asset('image/icon.png');
                        ?>
                        <img src="<?= $fotoUrl ?>" class="user-image" alt="User Image" onerror="this.src='<?= asset('image/icon.png') ?>'">
                        <span class="hidden-xs"><?= $user['name'] ?? 'Admin' ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <?php 
                            $fotoUrl = !empty($user['foto']) 
                                ? asset('image/users/' . $user['foto']) 
                                : asset('image/icon.png');
                            ?>
                            <img src="<?= $fotoUrl ?>" class="img-circle" alt="User Image" onerror="this.src='<?= asset('image/icon.png') ?>'">
                            <p>
                                <?= $user['name'] ?? 'Admin' ?> - Administrator
                                <small>Member Mulai <?= date('M. Y') ?></small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <form action="<?= url('/logout') ?>" method="POST" style="display: inline;">
                                    <input type="hidden" name="_token" value="<?= View::csrf() ?>">
                                    <button type="submit" class="btn btn-default btn-flat">Sign out</button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
