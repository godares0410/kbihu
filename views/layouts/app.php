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
    <script>
    // Fix aria-hidden issue for modals
    $(document).ready(function() {
        // Remove aria-hidden from wrapper when any modal is shown
        $(document).on("show.bs.modal", ".modal", function(e) {
            e.stopPropagation();
            $(".wrapper").removeAttr("aria-hidden");
            $(this).removeAttr("aria-hidden");
        });
        
        // Restore aria-hidden when all modals are hidden
        $(document).on("hidden.bs.modal", ".modal", function(e) {
            e.stopPropagation();
            // Use setTimeout to ensure modal is fully hidden
            setTimeout(function() {
                var visibleModals = $(".modal:visible").length;
                if (visibleModals === 0) {
                    $(".wrapper").attr("aria-hidden", "true");
                }
            }, 150);
        });
        
        // Also handle when modal is about to be shown (before animation)
        $(document).on("show.bs.modal", ".modal", function() {
            $(".wrapper").removeAttr("aria-hidden");
        });
    });
    </script>
</body>
</html>
