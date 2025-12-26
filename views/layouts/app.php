<!DOCTYPE html>
<html lang="en">
<?php include __DIR__ . '/head.php'; ?>

<body class="hold-transition skin-red sidebar-mini">
    <div class="wrapper">
        <?php include __DIR__ . '/header.php'; ?>
        <?php include __DIR__ . '/sidebar.php'; ?>

        <div class="content-wrapper">
            <?= $content ?>
        </div>

        <?php include __DIR__ . '/footer.php'; ?>
    </div>

    <?php include __DIR__ . '/scripts.php'; ?>
</body>
</html>
