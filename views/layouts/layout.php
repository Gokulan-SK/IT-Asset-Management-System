<?php include BASE_PATH . "views/layouts/components/head.php"; ?>

<div class="container">
    <?php include BASE_PATH . "views/layouts/components/header.php"; ?>
    <div class="main-container">
        <?php include BASE_PATH . "views/layouts/components/sidebar.php"; ?>
        <div class="workspace">
            <?php if (isset($viewToInclude) && file_exists($viewToInclude)) {
                include $viewToInclude;
            } else {
                echo "Content not found.";
            }
            ?>
        </div>
    </div>
</div>
<?php include BASE_PATH . "views/layouts/components/footer.php"; ?>