<?php
//asset/controllers/updateAssetController.php
require_once BASE_PATH . "asset/models/AssetModel.php";
require_once BASE_PATH . "utils/validators/AssetValidator.php";
$pageTitle = "Update Asset";
$viewToInclude = BASE_PATH . "asset/views/asset_form.php";
$action = "asset/update";

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
    if (!$id) {
        $errorMessage = "Asset ID required.";
        require_once BASE_PATH . "views/layouts/layout.php";
        exit;
    }

    $assetData = AssetModel::getAssetById($conn, $id);

    if (!$assetData) {
        $errorMessage = "Asset not found.";
        require_once BASE_PATH . "views/layouts/layout.php";
        exit;
    }

    $image = null;

    try {
        if (!empty($assetData["image_path"]) && file_exists($assetData["image_path"])) {
            $image = base64_encode(file_get_contents($assetData["image_path"]));
        }
    } catch (Exception $e) {
        error_log("Failed to load image: " . $e->getMessage());
    }

    $formData = [
        "name" => $assetData["name"],
        "category" => $assetData["category"],
        "subcategory" => $assetData["subcategory"],
        "purchaseDate" => $assetData["purchase_date"],
        "serialNumber" => $assetData["serial_number"],
        "licenseKey" => $assetData["license_key"],
        "licenseExpiry" => $assetData["license_expiry"],
        "warrantyPeriod" => $assetData["warranty_period"],
        "unitPrice" => $assetData["unit_price"],
        "assetStatus" => $assetData["asset_status"],
        "assetCondition" => $assetData["asset_condition"],
        "notes" => $assetData["notes"],
        "image" => $image,
    ];

    require_once BASE_PATH . "views/layouts/layout.php";
    exit;
}


?>