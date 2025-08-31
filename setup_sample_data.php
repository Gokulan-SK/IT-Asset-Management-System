<?php
// Sample data insertion script for dashboard testing
require_once __DIR__ . '/config/database.php';

echo "<h2>Adding Sample Data for Dashboard Testing</h2>";

try {
    // Check if data already exists
    $result = $conn->query("SELECT COUNT(*) as count FROM asset");
    $assetCount = $result->fetch_assoc()['count'];

    if ($assetCount > 0) {
        echo "<p>Assets already exist ($assetCount found). Skipping asset creation.</p>";
    } else {
        // Insert sample assets
        echo "<h3>Adding Sample Assets...</h3>";

        $assets = [
            ['Dell Laptop', 'hardware', 'laptop', '2024-01-15', 'DL001', '', '', 24, 1200.00, 'available', 'excellent'],
            ['HP Printer', 'hardware', 'printer', '2024-02-10', 'HP001', '', '', 12, 300.00, 'available', 'good'],
            ['Microsoft Office 365', 'software', 'productivity', '2024-01-01', '', 'MS365-001', '2025-01-01', 0, 150.00, 'available', 'excellent'],
            ['Standing Desk', 'office-equipment', 'furniture', '2024-03-01', 'SD001', '', '', 60, 400.00, 'in-use', 'good'],
            ['MacBook Pro', 'hardware', 'laptop', '2024-01-20', 'MBP001', '', '', 36, 2500.00, 'in-use', 'excellent'],
            ['Adobe Creative Suite', 'software', 'design', '2024-02-01', '', 'ACS-001', '2025-02-01', 0, 600.00, 'in-use', 'excellent'],
            ['Conference Table', 'office-equipment', 'furniture', '2023-12-01', 'CT001', '', '', 120, 800.00, 'available', 'good'],
            ['Windows Server License', 'software', 'operating-system', '2024-01-01', '', 'WS-001', '2026-01-01', 0, 1000.00, 'available', 'excellent']
        ];

        $stmt = $conn->prepare("INSERT INTO asset (name, category, subcategory, purchase_date, serial_number, license_key, license_expiry, warranty_period, unit_price, asset_status, asset_condition, notes, image_path, is_deleted) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '', '', 0)");

        foreach ($assets as $asset) {
            $stmt->bind_param("sssssssiiss", ...$asset);
            if ($stmt->execute()) {
                echo "<li>Added: {$asset[0]} ({$asset[1]})</li>";
            }
        }
        $stmt->close();
        echo "<p>✅ Sample assets added successfully!</p>";
    }

    // Check employees
    $result = $conn->query("SELECT COUNT(*) as count FROM employee WHERE is_deleted = 0");
    $empCount = $result->fetch_assoc()['count'];

    if ($empCount > 0) {
        echo "<p>Employees already exist ($empCount found). Skipping employee creation.</p>";
    } else {
        // Insert sample employees
        echo "<h3>Adding Sample Employees...</h3>";

        $employees = [
            ['john.doe', 'John', 'Doe', 'john.doe@company.com', '555-0101', '1990-05-15', 'dev', 0],
            ['jane.smith', 'Jane', 'Smith', 'jane.smith@company.com', '555-0102', '1988-08-22', 'designer', 0],
            ['admin.user', 'Admin', 'User', 'admin@company.com', '555-0100', '1985-03-10', 'manager', 1],
            ['mike.johnson', 'Mike', 'Johnson', 'mike.j@company.com', '555-0103', '1992-11-30', 'it-support', 0],
            ['sarah.wilson', 'Sarah', 'Wilson', 'sarah.w@company.com', '555-0104', '1991-07-18', 'hr', 0]
        ];

        $defaultPassword = password_hash('password123', PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO employee (username, first_name, last_name, email, phone, dob, designation, is_admin, password_hash, is_deleted) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");

        foreach ($employees as $emp) {
            $stmt->bind_param("sssssssis", $emp[0], $emp[1], $emp[2], $emp[3], $emp[4], $emp[5], $emp[6], $emp[7], $defaultPassword);
            if ($stmt->execute()) {
                echo "<li>Added: {$emp[1]} {$emp[2]} ({$emp[6]})</li>";
            }
        }
        $stmt->close();
        echo "<p>✅ Sample employees added successfully!</p>";
    }

    // Check ledger entries
    $result = $conn->query("SELECT COUNT(*) as count FROM asset_ledger WHERE is_deleted = 0");
    $ledgerCount = $result->fetch_assoc()['count'];

    if ($ledgerCount > 0) {
        echo "<p>Asset ledger entries already exist ($ledgerCount found). Skipping ledger creation.</p>";
    } else {
        // Add some sample asset allocations
        echo "<h3>Adding Sample Asset Allocations...</h3>";

        // Get asset and employee IDs
        $assetResult = $conn->query("SELECT asset_id, name FROM asset WHERE asset_status = 'in-use' LIMIT 3");
        $empResult = $conn->query("SELECT emp_id, first_name, last_name FROM employee WHERE is_deleted = 0 LIMIT 3");

        $assets = [];
        $employees = [];

        while ($row = $assetResult->fetch_assoc()) {
            $assets[] = $row;
        }
        while ($row = $empResult->fetch_assoc()) {
            $employees[] = $row;
        }

        if (!empty($assets) && !empty($employees)) {
            $stmt = $conn->prepare("INSERT INTO asset_ledger (asset_id, emp_id, check_out_date, purpose, is_deleted) VALUES (?, ?, ?, ?, 0)");

            $allocations = [
                [$assets[0]['asset_id'], $employees[0]['emp_id'], '2024-07-01', 'Development work'],
                [$assets[1]['asset_id'], $employees[1]['emp_id'], '2024-07-15', 'Design projects'],
                [$assets[2]['asset_id'] ?? $assets[0]['asset_id'], $employees[2]['emp_id'], '2024-08-01', 'Administrative tasks']
            ];

            foreach ($allocations as $alloc) {
                $stmt->bind_param("iiss", $alloc[0], $alloc[1], $alloc[2], $alloc[3]);
                if ($stmt->execute()) {
                    echo "<li>Allocated asset ID {$alloc[0]} to employee ID {$alloc[1]}</li>";
                }
            }
            $stmt->close();
            echo "<p>✅ Sample allocations added successfully!</p>";
        }
    }

    echo "<hr>";
    echo "<h3>Dashboard Data Summary:</h3>";

    // Show final counts
    $assetResult = $conn->query("SELECT COUNT(*) as total, 
                                        SUM(CASE WHEN asset_status = 'available' THEN 1 ELSE 0 END) as available,
                                        SUM(CASE WHEN asset_status = 'in-use' THEN 1 ELSE 0 END) as in_use 
                                 FROM asset WHERE is_deleted = 0");
    $assetStats = $assetResult->fetch_assoc();

    $empResult = $conn->query("SELECT COUNT(*) as total FROM employee WHERE is_deleted = 0");
    $empStats = $empResult->fetch_assoc();

    $ledgerResult = $conn->query("SELECT COUNT(*) as total FROM asset_ledger WHERE is_deleted = 0");
    $ledgerStats = $ledgerResult->fetch_assoc();

    echo "<ul>";
    echo "<li><strong>Total Assets:</strong> {$assetStats['total']} (Available: {$assetStats['available']}, In Use: {$assetStats['in_use']})</li>";
    echo "<li><strong>Total Employees:</strong> {$empStats['total']}</li>";
    echo "<li><strong>Total Allocations:</strong> {$ledgerStats['total']}</li>";
    echo "</ul>";

    echo "<p><a href='/asset_management/dashboard' class='btn-primary' style='display: inline-block; padding: 10px 15px; background: #3B82F6; color: white; text-decoration: none; border-radius: 4px; margin-top: 20px;'>View Dashboard</a></p>";

} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>