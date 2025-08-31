<?php
require_once __DIR__ . '/config/database.php';

echo "<h2>Database Status Check</h2>";

try {
    // Check asset table
    $result = $conn->query("SELECT COUNT(*) as count FROM asset");
    $assetCount = $result->fetch_assoc()['count'];
    echo "<p>Assets in database: <strong>$assetCount</strong></p>";

    // Check employee table
    $result = $conn->query("SELECT COUNT(*) as count FROM employee");
    $employeeCount = $result->fetch_assoc()['count'];
    echo "<p>Employees in database: <strong>$employeeCount</strong></p>";

    // Check asset_ledger table
    $result = $conn->query("SELECT COUNT(*) as count FROM asset_ledger");
    $ledgerCount = $result->fetch_assoc()['count'];
    echo "<p>Asset ledger entries: <strong>$ledgerCount</strong></p>";

    // Show sample asset data
    if ($assetCount > 0) {
        echo "<h3>Sample Assets:</h3>";
        $result = $conn->query("SELECT name, category, asset_status FROM asset LIMIT 5");
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>{$row['name']} - {$row['category']} - {$row['asset_status']}</li>";
        }
        echo "</ul>";
    }

    // Show sample employee data
    if ($employeeCount > 0) {
        echo "<h3>Sample Employees:</h3>";
        $result = $conn->query("SELECT CONCAT(first_name, ' ', last_name) as name, designation FROM employee WHERE is_deleted = 0 LIMIT 5");
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>{$row['name']} - {$row['designation']}</li>";
        }
        echo "</ul>";
    }

    if ($assetCount == 0 || $employeeCount == 0) {
        echo "<div style='background: #FEF3C7; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
        echo "<strong>Note:</strong> To see the full dashboard analytics, you'll need to add some sample data:";
        echo "<ul>";
        echo "<li><a href='/asset_management/asset/add'>Add Assets</a></li>";
        echo "<li><a href='/asset_management/employee/add'>Add Employees</a></li>";
        echo "<li>Then use <a href='/asset_management/asset-ledger'>Asset Ledger</a> to allocate assets</li>";
        echo "</ul>";
        echo "</div>";
    }

} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<p><a href='/asset_management/dashboard'>Go to Dashboard</a></p>";
?>