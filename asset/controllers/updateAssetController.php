<?php
//asset/controllers/updateAssetController.php
require_once BASE_PATH . "asset/models/AssetModel.php";
require_once BASE_PATH . "utils/validators/AssetValidator.php";
$pageTitle = "Update Asset";
$viewToInclude = BASE_PATH . "asset/views/asset_form.php";
$pageScripts = [
    BASE_URL . "public/js/asset/add-asset.js",
];
$action = "asset/update";
$errorMessage = null;
$successMessage = null;

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
        "asset_id" => $id,
        "name" => $assetData["name"],
        "category" => $assetData["category"],
        "subcategory" => $assetData["subcategory"],
        "purchaseDate" => $assetData["purchase_date"],
        "serialNumber" => $assetData["serial_number"],
        "licenseKey" => $assetData["license_key"],
        "licenseExpiry" => $assetData["license_expiry"],
        "warrantyPeriod" => $assetData["warranty_period"],
        "unitPrice" => $assetData["unit_price"],
        "status" => $assetData["asset_status"],
        "assetCondition" => $assetData["asset_condition"],
        "notes" => $assetData["notes"],
        "image" => $image,
    ];

    require_once BASE_PATH . "views/layouts/layout.php";
    exit;
} else if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = (int) $_POST["id"];
    $imageFile = $_FILES['asset-image'] ?? '';
    $imagePath = AssetHelper::handleImageUpload($imageFile, BASE_PATH . "public/uploads/asset/asset-images/");

    $formData = [
        "name" => trim(string: $_POST['name'] ?? null),
        "category" => trim($_POST['category'] ?? null),
        "subcategory" => trim($_POST['subcategory'] ?? null),
        "purchase-date" => $_POST['purchase-date'] ?? null,
        "serial-number" => trim($_POST['serial-number'] ?? null) ?: null,
        "license-key" => !empty(trim($_POST['license-key'])) ? trim($_POST['license-key']) : null,
        "license-expiry" => $_POST['license-expiry'] ?? null,
        "warranty-period" => is_numeric($_POST['warranty-period']) ? (int) $_POST['warranty-period'] : null,
        "unit-price" => is_numeric($_POST['unit-price']) ? (int) $_POST['unit-price'] : null,
        "status" => trim($_POST['status'] ?? null),
        "condition" => trim($_POST['condition'] ?? null),
        "notes" => trim($_POST['notes'] ?? null),
        "image" => $imagePath["path"] ?? null
    ];
    if (isset($imagePath["imageError"])) {
        $errors['imageError'] = $imagePath["imageError"];
    }

    // $errors = AssetValidator::validateForUpdate($formData);

    if (!empty($errors)) {
        $errorMessage = "Errors found in the form data!";
        require_once BASE_PATH . "views/layouts/layout.php";
        exit;
    }

    $updated = AssetModel::update($conn, $formData, $id);

    if ($updated) {
        $_SESSION['success'] = "Asset updated successfully.";
        header("Location: " . BASE_URL . "asset/view");
        exit;
    } else {
        $_SESSION['error'] = "Error updating asset. Please try again.";
        header("Location: " . BASE_URL . "asset/add");
        exit;
    }


}

?>