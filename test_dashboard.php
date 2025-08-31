<?php
// Test script to verify dashboard data
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/dashboard-module/models/DashboardModel.php';

echo "<h2>Dashboard Data Test</h2>";

// Test asset summary
echo "<h3>Asset Summary:</h3>";
$assetSummary = DashboardModel::getAssetSummary($conn);
echo "<pre>" . print_r($assetSummary, true) . "</pre>";

// Test asset categories
echo "<h3>Assets by Category:</h3>";
$assetsByCategory = DashboardModel::getAssetsByCategory($conn);
echo "<pre>" . print_r($assetsByCategory, true) . "</pre>";

// Test employee summary
echo "<h3>Employee Summary:</h3>";
$employeeSummary = DashboardModel::getEmployeeSummary($conn);
echo "<pre>" . print_r($employeeSummary, true) . "</pre>";

// Test recent activity
echo "<h3>Recent Activity:</h3>";
$recentActivity = DashboardModel::getRecentActivity($conn, 5);
echo "<pre>" . print_r($recentActivity, true) . "</pre>";

echo "<p><a href='/asset_management/dashboard'>Back to Dashboard</a></p>";
?>