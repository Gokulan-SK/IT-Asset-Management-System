<?php
// asset/controllers/viewAssetController.php

require_once BASE_PATH . "asset/models/AssetModel.php";
require_once BASE_PATH . "employee/models/EmployeeModel.php"; // For future "assigned to" lookup, if needed

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $page = isset($_GET["page"]) ? (int) $_GET['page'] : 1;
    $limit = isset($_GET["limit"]) ? (int) $_GET['limit'] : 10;
    $limit = in_array($limit, [10, 25, 50, 100]) ? $limit : 10; // Validate limit
    $offset = ($page - 1) * $limit;

    // Get search, filter, and sort parameters
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

    // Handle export request
    if ($export === 'csv') {
        try {
            error_log("Export CSV requested - Search: '$search', Status Filter: '$statusFilter', Category Filter: '$categoryFilter'");
            $exportData = AssetModel::exportAssets($conn, $search, $statusFilter, $categoryFilter);
            error_log("Export data count: " . count($exportData));

            if (empty($exportData)) {
                error_log("No export data found, redirecting with error");
                $_SESSION['error'] = "No asset data found to export.";
                header("Location: " . BASE_URL . "asset/view");
                exit;
            }

            // Set headers for CSV download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="assets_' . date('Y-m-d_H-i-s') . '.csv"');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');

            // Output CSV headers
            $csvHeaders = ['Asset ID', 'Asset Name', 'Category', 'Status', 'Condition', 'Purchase Date', 'Purchase Cost', 'Assigned Employee'];
            echo implode(',', $csvHeaders) . "\n";

            // Output CSV data
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
            error_log("Export error: " . $e->getMessage());
            $_SESSION['error'] = "Error exporting asset data: " . $e->getMessage();
            header("Location: " . BASE_URL . "asset/view");
            exit;
        }
    }

    try {
        // Total asset records with filters
        $totalRecordsCount = AssetModel::getAssetCount($conn, $search, $statusFilter, $categoryFilter);
        $totalPages = max(1, ceil($totalRecordsCount / $limit));

        if ($totalRecordsCount == 0) {
            $currentPage = 1;
            $totalPages = 1;
        } else {
            $totalPages = max(1, ceil($totalRecordsCount / $limit));
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
            $assets = AssetModel::getPaginatedAssetList($conn, $limit, $offset, $search, $statusFilter, $categoryFilter, $sort, $order);
            $currentPage = $page;
        }

    } catch (Exception $e) {
        $paginationError = "Error fetching asset data. Please try again.";
    }

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
