<script src="<?= BASE_URL ?>public/js/workspace.js"></script>
<?php
if (isset($pageScripts)) {
    foreach ($pageScripts as $script) {
        echo "<script src='" . BASE_URL . $script . "'></script>\n";
    }
}
?>
</body>

</html>