<?php

require_once BASE_PATH . "utils/validators/AssetLedgerValidator.php";
require_once BASE_PATH . "asset-ledger/models/AssetLedgerModel.php";
require_once BASE_PATH . "asset/models/AssetModel.php";

$pageTitle = "Check-Out Asset";
$viewToInclude = BASE_PATH . "asset-ledger/views/check_out_form.php";
$pageScripts = [
    "https://code.jquery.com/jquery-3.6.0.min.js", //library for jqery
    "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js", //library for select2
    BASE_URL . "public/js/asset_ledger/check_out_form.js", //script for check-out form dynamic searching
];
$pageStyles = [
    "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css", //library for select2 styles
];

$errorMessage = null;
$successMessage = null;

if (isset($_SESSION['errorMessage'])) {
    $errorMessage = $_SESSION['errorMessage'];
    unset($_SESSION['errorMessage']);
}
if (isset($_SESSION['successMessage'])) {
    $successMessage = $_SESSION['successMessage'];
    unset($_SESSION['successMessage']);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    require_once BASE_PATH . "views/layouts/layout.php";
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $assetId = (int) $_POST['asset'];
    $empId = (int) $_POST['employee-id'];
    $checkoutDate = $_POST['checkout-date'];
    $comments = $_POST['comments'];
    $assignedBy = (int) $_POST['assigned-by'];

    // $errors = AssetLedgerValidator::validateCheckout($assetId, $empId, $assignedBy $checkoutDate, $comments

    $result1 = AssetLedgerModel::checkout($conn, $assetId, $empId, $assignedBy, $checkoutDate, $comments);

    $category = AssetModel::getCategoryById($conn, $assetId);

    $result2 = AssetModel::updateAssetStatus($conn, $assetId, $category === "software" ? "active" : "in_use");

    if ($result1 && $result2) {
        $_SESSION['successMessage'] = "Asset checked out successfully.";
        header("location: " . BASE_URL . "asset-ledger/check-out");
        exit;
    } else {
        $_SESSION["errorMessage"] = "Failed to check out asset. Please try again.";
        // $_SESSION["errors"] = $errors;
        header("location:" . BASE_URL . "asset-ledger/check-out");
        exit;
    }

}


?>