<?php
ob_start();
session_start();
?>

<?php include BASE_PATH . "views/layout/components/head.php"; ?>

<div class="container">
    <?php include BASE_PATH . "views/layout/components/header.php"; ?>
    <div class="main-container">
        <?php include BASE_PATH . "views/layout/components/sidebar.php"; ?>
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
<?php include BASE_PATH . "views/layout/components/footer.php"; ?>