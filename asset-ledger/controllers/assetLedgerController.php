<?php
// asset-ledger/controllers/assetLedgerController.php

require_once BASE_PATH . "asset-ledger/models/AssetLedgerModel.php";
require_once BASE_PATH . "employee/models/EmployeeModel.php";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;

    $paginationError = null;
    $ledgers = [];
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
        // Count total ledger records
        $totalRecordsCount = AssetLedgerModel::getLedgerCount($conn);
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

        if ($page < 1 || $page > $totalPages) {
            $paginationError = "Invalid page number. Please try again.";
        } else {
            $ledgers = AssetLedgerModel::getPaginatedLedgerList($conn, $limit, $offset);
        }

    } catch (Exception $e) {
        $paginationError = "Error fetching ledger data. Please try again.";
    }

    $pageTitle = "Asset Ledger";
    $viewToInclude = BASE_PATH . "asset-ledger/views/asset_ledger.php";
    $pageScripts = [
        BASE_URL . "public/js/components/modal.js"
    ];

    require BASE_PATH . "views/layouts/layout.php";
    exit;
}
