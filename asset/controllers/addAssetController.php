<?php

require_once BASE_PATH . "asset/helpers/get_categories.php";

$viewToInclude = BASE_PATH . "asset/views/asset_form.php";
$pageTitle = "Add Asset";
$pageScripts = [
    "public/js/asset/add-asset.js",
];
$action = "asset/add";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $success = $_SESSION['success'] ?? '';
    $error = $_SESSION['error'] ?? '';
    unset($_SESSION['error']);
    unset($_SESSION['success']);
    require_once BASE_PATH . "views/layouts/layout.php";
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $forData = $_POST;


}

?>