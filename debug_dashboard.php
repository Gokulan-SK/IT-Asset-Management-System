<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/dashboard-module/models/DashboardModel.php';

echo "<h3>Dashboard Data Debug</h3>";

try {
    // Test database connection
    echo "<p>Database connection: " . ($conn ? "✅ Connected" : "❌ Failed") . "</p>";
    
    // Check asset data
    $assetSummary = DashboardModel::getAssetSummary($conn);
    echo "<h4>Asset Summary:</h4>";
    echo "<pre>" . print_r($assetSummary, true) . "</pre>";
    
    // Check categories
    $assetsByCategory = DashboardModel::getAssetsByCategory($conn);
    echo "<h4>Assets by Category:</h4>";
    echo "<pre>" . print_r($assetsByCategory, true) . "</pre>";
    
    // Check employees
    $employeeSummary = DashboardModel::getEmployeeSummary($conn);
    echo "<h4>Employee Summary:</h4>";
    echo "<pre>" . print_r($employeeSummary, true) . "</pre>";
    
    // Check employee roles
    $employeesByDesignation = DashboardModel::getEmployeesByDesignation($conn);
    echo "<h4>Employees by Designation:</h4>";
    echo "<pre>" . print_r($employeesByDesignation, true) . "</pre>";
    
    // Check monthly trend
    $monthlyTrend = DashboardModel::getMonthlyAllocationTrend($conn, 6);
    echo "<h4>Monthly Trend:</h4>";
    echo "<pre>" . print_r($monthlyTrend, true) . "</pre>";
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
?>
