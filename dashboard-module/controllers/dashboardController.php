<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/DashboardModel.php';

// Initialize data with defaults
$assetSummary = ['total_assets' => 0, 'available_assets' => 0, 'in_use_assets' => 0, 'maintenance_assets' => 0, 'retired_assets' => 0];
$assetsByCategory = [];
$assetsByCondition = [];
$assetValueSummary = ['total_value' => 0, 'average_value' => 0, 'highest_value' => 0, 'lowest_value' => 0];
$employeeSummary = ['total_employees' => 0, 'admin_employees' => 0];
$employeesByDesignation = [];
$ledgerSummary = ['total_allocations' => 0, 'active_allocations' => 0, 'returned_allocations' => 0];
$recentActivity = [];
$assetUtilization = [];
$warrantyExpiring = [];
$monthlyTrend = [];
$utilizationRate = ['total_assets' => 0, 'assets_in_use' => 0, 'utilization_percentage' => 0];
$agingAssets = [];
$topCategoriesByUsage = [];
$assetValueByStatus = [];

try {
    // Get all dashboard analytics data
    $assetSummary = DashboardModel::getAssetSummary($conn) ?: $assetSummary;
    $assetsByCategory = DashboardModel::getAssetsByCategory($conn);
    $assetStatusDistribution = DashboardModel::getAssetStatusDistribution($conn);
    $assetsByCondition = DashboardModel::getAssetsByCondition($conn);
    $assetValueSummary = DashboardModel::getAssetValueSummary($conn) ?: $assetValueSummary;
    $employeeSummary = DashboardModel::getEmployeeSummary($conn) ?: $employeeSummary;
    $employeesByDesignation = DashboardModel::getEmployeesByDesignation($conn);
    $ledgerSummary = DashboardModel::getLedgerSummary($conn) ?: $ledgerSummary;
    $recentActivity = DashboardModel::getRecentActivity($conn, 10);
    $assetUtilization = DashboardModel::getAssetUtilization($conn);
    $warrantyExpiring = DashboardModel::getWarrantyExpiring($conn, 90);
    $monthlyTrend = DashboardModel::getMonthlyAllocationTrend($conn, 6);
    $utilizationRate = DashboardModel::getAssetUtilizationRate($conn) ?: $utilizationRate;
    $agingAssets = DashboardModel::getAgingAssets($conn, 3);
    $topCategoriesByUsage = DashboardModel::getTopCategoriesByUsage($conn);
    $assetValueByStatus = DashboardModel::getAssetValueByStatus($conn);
} catch (Exception $e) {
    error_log("Dashboard data loading error: " . $e->getMessage());
    // Continue with default values
}

$viewToInclude = BASE_PATH . "dashboard-module/views/dashboard.php";
$pageStyles = [
    "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css",
    BASE_URL . "public/css/pages/dashboard.css",
];
$pageScripts = [
    "https://cdn.jsdelivr.net/npm/chart.js",
    "https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js",
    BASE_URL . "public/js/dashboard/dashboard-fixed.js"
];
$pageTitle = "Dashboard";

require_once BASE_PATH . "views/layouts/layout.php";
?>