<?php
// asset-ledger/controllers/assetLedgerController.php

$pageTitle = "Asset Ledger";
$viewToInclude = BASE_PATH . "asset-ledger/views/asset_ledger.php";

$errorMessage = null;
$successMessage = null;

if (isset($_SESSION['error'])) {
    $errorMessage = $_SESSION['error'];
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    $successMessage = $_SESSION['success'];
    unset($_SESSION['success']);
}

require_once BASE_PATH . "views/layouts/layout.php"

    ?>