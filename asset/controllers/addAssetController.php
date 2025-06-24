<?php

require_once BASE_PATH . "asset/helpers/AssetHelper.php";
require_once BASE_PATH . "asset/models/AssetModel.php";

$viewToInclude = BASE_PATH . "asset/views/asset_form.php";
$pageTitle = "Add Asset";
$pageScripts = [
    "public/js/asset/add-asset.js",
];
$action = "asset/add";
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


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    require_once BASE_PATH . "views/layouts/layout.php";
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = $_POST;

    $imageFile = $_FILES['asset-image'] ?? '';
    $imagePath = AssetHelper::handleImageUpload($imageFile, BASE_PATH . "public/uploads/asset/asset-images/");


    $data = [
        "name" => trim($formData['name'] ?? null),
        "category" => trim($formData['category'] ?? null),
        "subcategory" => trim($formData['subcategory'] ?? null),
        "purchase-date" => $formData['purchase-date'] ?? null,
        "serial-number" => trim($formData['serial-number'] ?? null) ?: null,
        "license-key" => trim($formData['license-key'] ?? null) ?: null,
        "license-expiry" => $formData['license-expiry'] ?? null,
        "warranty-period" => is_numeric($formData['warranty-period']) ? (int) $formData['warranty-period'] : null,
        "unit-price" => is_numeric($formData['unit-price']) ? (float) $formData['unit-price'] : null,
        "status" => trim($formData['status'] ?? null),
        "condition" => trim($formData['condition'] ?? null),
        "notes" => trim($formData['notes'] ?? null),
        "image" => $imagePath,
    ];

    //validate data
    // $errors = AssetValidator::validateForCreate($conn, $data);

    if ($errors !== null) {
        $errorMessage = "Errors found in the form data!";
        header("Location: " . BASE_URL . "asset/add");
        exit;
    }

    $created = AssetModel::create($conn, $data);

    if ($created) {
        $_SESSION['success'] = "Asset added successfully.";
        header("Location: " . BASE_URL . "asset/add");
        exit;
    } else {
        $_SESSION['error'] = "Error adding asset. Please try again.";
        header("Location: " . BASE_URL . "asset/add");
        exit;
    }
}

?>