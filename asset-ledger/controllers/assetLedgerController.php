<?php
// asset-ledger/controllers/assetLedgerController.php

require_once BASE_PATH . "asset-ledger/models/AssetLedgerModel.php";
require_once BASE_PATH . "employee/models/EmployeeModel.php";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Handle export request
    if (isset($_GET['export']) && $_GET['export'] === 'csv') {
        $search = $_GET['search'] ?? '';
        $statusFilter = $_GET['statusFilter'] ?? '';

        $ledgers = AssetLedgerModel::exportLedgers($conn, $search, $statusFilter);

        // Set CSV headers
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="asset_ledger_' . date('Y-m-d_H-i-s') . '.csv"');

        $output = fopen('php://output', 'w');

        // CSV headers
        fputcsv($output, [
            'Ledger ID',
            'Asset ID',
            'Asset Name',
            'Employee ID',
            'Employee Name',
            'Check-out Date',
            'Check-in Date',
            'Status',
            'Comments'
        ]);

        // CSV data
        foreach ($ledgers as $ledger) {
            fputcsv($output, [
                $ledger['ledger_id'],
                $ledger['asset_id'],
                $ledger['asset_name'],
                $ledger['emp_id'],
                $ledger['employee_name'],
                $ledger['check_out_date'],
                $ledger['check_in_date'] ?? '-',
                $ledger['status'],
                $ledger['comments'] ?? ''
            ]);
        }

        fclose($output);
        exit;
    }

    // Get parameters
    $page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
    $limit = isset($_GET["limit"]) ? (int) $_GET["limit"] : 10;
    $search = $_GET['search'] ?? '';
    $statusFilter = $_GET['statusFilter'] ?? '';
    $sort = $_GET['sort'] ?? 'ledger_id';
    $order = $_GET['order'] ?? 'DESC';

    // Validate limit
    if (!in_array($limit, [10, 25, 50])) {
        $limit = 10;
    }

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
        // Count total ledger records with filters
        $totalRecordsCount = AssetLedgerModel::getLedgerCountWithFilters($conn, $search, $statusFilter);
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
            $ledgers = AssetLedgerModel::getPaginatedLedgerListWithFilters($conn, $limit, $offset, $search, $statusFilter, $sort, $order);
        }

    } catch (Exception $e) {
        $paginationError = "Error fetching ledger data. Please try again.";
    }

    $pageTitle = "Asset Ledger";
    $viewToInclude = BASE_PATH . "asset-ledger/views/asset_ledger.php";
    $pageStyles = [
        BASE_URL . "public/css/pages/employee-list.css?v=" . (time() + 1),
    ];
    $pageScripts = [
        BASE_URL . "public/js/asset-ledger/asset-ledger.js?v=" . (time() + 2),
    ];

    require BASE_PATH . "views/layouts/layout.php";
    exit;
}
