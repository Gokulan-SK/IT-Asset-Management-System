<script src="<?= BASE_URL ?>public/js/workspace.js"></script>
<?php
if (isset($pageScripts)) {
    foreach ($pageScripts as $script) {
        echo "<link rel='stylesheet' href='$script' />\n";
    }
}
?>
</body>

</html>