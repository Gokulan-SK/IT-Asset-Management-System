<?php
// asset/controllers/viewAssetController.php

require_once BASE_PATH . "asset/models/AssetModel.php";
require_once BASE_PATH . "employee/models/EmployeeModel.php"; // for future "assigned to" lookup

if ($_SERVER["REQUEST_METHOD"] === "GET") {

    // --- GET PARAMETERS ---
    $page = isset($_GET["page"]) ? (int) $_GET['page'] : 1;
    $limit = isset($_GET["limit"]) ? (int) $_GET['limit'] : 10;
    $limit = in_array($limit, [10, 25, 50, 100]) ? $limit : 10; // Validate limit

    $search = isset($_GET["search"]) ? trim($_GET["search"]) : '';
    $statusFilter = isset($_GET["statusFilter"]) ? trim($_GET["statusFilter"]) : '';
    $categoryFilter = isset($_GET["categoryFilter"]) ? trim($_GET["categoryFilter"]) : '';
    $sort = isset($_GET["sort"]) ? trim($_GET["sort"]) : 'asset_id';
    $order = isset($_GET["order"]) && in_array($_GET["order"], ['ASC', 'DESC']) ? $_GET["order"] : 'ASC';
    $export = isset($_GET["export"]) ? $_GET["export"] : false;

    $paginationError = null;
    $assets = [];
    $totalRecordsCount = 0;
    $totalPages = 1;
    $currentPage = 1;
    $errorMessage = null;
    $successMessage = null;

    // --- FLASH MESSAGES ---
    if (isset($_SESSION['success'])) {
        $successMessage = $_SESSION['success'];
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        $errorMessage = $_SESSION['error'];
        unset($_SESSION['error']);
    }

    // --- EXPORT HANDLING ---
    if ($export === 'csv') {
        try {
            $exportData = AssetModel::exportAssets($conn, $search, $statusFilter, $categoryFilter);
            if (empty($exportData)) {
                $_SESSION['error'] = "No asset data found to export.";
                header("Location: " . BASE_URL . "asset/view");
                exit;
            }

            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="assets_' . date('Y-m-d_H-i-s') . '.csv"');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');

            $csvHeaders = ['Asset ID', 'Asset Name', 'Category', 'Status', 'Condition', 'Purchase Date', 'Purchase Cost', 'Assigned Employee'];
            echo implode(',', $csvHeaders) . "\n";

            foreach ($exportData as $row) {
                $csvRow = [
                    $row['asset_id'],
                    '"' . str_replace('"', '""', $row['asset_name']) . '"',
                    '"' . str_replace('"', '""', $row['category']) . '"',
                    '"' . str_replace('"', '""', $row['status']) . '"',
                    '"' . str_replace('"', '""', $row['condition_status']) . '"',
                    $row['purchase_date'],
                    $row['purchase_cost'],
                    '"' . str_replace('"', '""', $row['employee_name'] ?? 'Not Assigned') . '"'
                ];
                echo implode(',', $csvRow) . "\n";
            }
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = "Error exporting asset data: " . $e->getMessage();
            header("Location: " . BASE_URL . "asset/view");
            exit;
        }
    }

    try {
        // --- TOTAL RECORDS & PAGES ---
        $totalRecordsCount = AssetModel::getAssetCount($conn, $search, $statusFilter, $categoryFilter);
        $totalPages = max(1, ceil($totalRecordsCount / $limit));

        // Correct current page if out of range
        if ($page < 1) {
            $currentPage = 1;
            header("Location: " . BASE_URL . "asset/view?page=1");
            exit;
        } elseif ($page > $totalPages) {
            $currentPage = $totalPages;
            header("Location: " . BASE_URL . "asset/view?page=$currentPage");
            exit;
        } else {
            $currentPage = $page;
        }

        $offset = ($currentPage - 1) * $limit;

        // --- FETCH PAGINATED ASSETS ---
        if ($totalRecordsCount > 0) {
            $assets = AssetModel::getPaginatedAssetList(
                $conn,
                $limit,
                $offset,
                $search,
                $statusFilter,
                $categoryFilter,
                $sort,
                $order
            );
        }

    } catch (Exception $e) {
        $paginationError = "Error fetching asset data. Please try again.";
    }

    // --- PAGE VARIABLES FOR VIEW ---
    $pageTitle = "View Assets";
    $viewToInclude = BASE_PATH . "asset/views/asset_list.php";
    $pageStyles = [
        BASE_URL . "public/css/pages/employee-list.css?v=" . (time() + 1),
    ];
    $pageScripts = [
        BASE_URL . "public/js/asset/asset-list.js?v=" . (time() + 2),
    ];
    require BASE_PATH . "views/layouts/layout.php";
    exit;
}
