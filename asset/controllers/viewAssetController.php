<?php
// asset/controllers/viewAssetController.php

require_once BASE_PATH . "asset/models/AssetModel.php";
require_once BASE_PATH . "employee/models/EmployeeModel.php"; // For future "assigned to" lookup, if needed

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;

    $paginationError = null;
    $assets = [];
    $totalRecordsCount = 0;
    $totalPages = 1;
    $errorMessage = null;
    $successMessage = null;

    // Flash success message
    if (isset($_SESSION['success'])) {
        $successMessage = $_SESSION['success'];
        unset($_SESSION['success']);
    }

    // Flash error message
    if (isset($_SESSION['error'])) {
        $errorMessage = $_SESSION['error'];
        unset($_SESSION['error']);
    }

    try {
        // Total asset records
        $totalRecordsCount = AssetModel::getAssetCount($conn);
        $totalPages = max(1, ceil($totalRecordsCount / $limit));

        if ($totalRecordsCount == 0) {
            $currentPage = 1;
        } else {
            if ($page < 1) {
                $currentPage = 1;
            } elseif ($page > $totalPages) {
                $currentPage = $totalPages;
            } else {
                $currentPage = $page;
            }
        }

        // If page is out of range
        if ($page < 1 || $page > $totalPages) {
            $paginationError = "Invalid page number. Please try again.";
        } else {
            $assets = AssetModel::getPaginatedAssetList($conn, $limit, $offset);
        }

    } catch (Exception $e) {
        $paginationError = "Error fetching asset data. Please try again.";
    }

    $pageTitle = "View Assets";
    $viewToInclude = BASE_PATH . "asset/views/asset_list.php";
    $pageScripts = [
        BASE_URL . "public/js/components/modal.js"
    ];
    require BASE_PATH . "views/layouts/layout.php";
    exit;
}
