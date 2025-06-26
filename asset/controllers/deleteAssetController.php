<?php

require_once BASE_PATH . "utils/ValidationHelper.php";
require_once BASE_PATH . "asset/models/AssetModel.php";

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION["error"] = "Invalid request method.";
    header("Location: " . BASE_URL . "asset/view");
    exit;
}

// Get asset ID from POST data
$asset_id = $_POST["id"] ?? null;

if (!ValidationHelper::isNumeric($asset_id)) {
    $_SESSION["error"] = "Invalid asset ID.";
    header("Location: " . BASE_URL . "asset/view");
    exit;
}

$asset_id = (int) $asset_id;

try {
    $deleted = AssetModel::deleteAssetById($conn, $asset_id);

    if ($deleted) {
        $_SESSION["success"] = "Asset deleted successfully.";
    } else {
        $_SESSION["error"] = "Error deleting asset. Please try again.";
    }

    header("Location: " . BASE_URL . "asset/view");
    exit;

} catch (Exception $e) {
    error_log("Asset deletion failed: " . $e->getMessage());
    $_SESSION["error"] = "Internal error while deleting asset.";
    header("Location: " . BASE_URL . "asset/view");
    exit;
}
