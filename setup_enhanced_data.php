<?php
// Enhanced sample data for comprehensive dashboard testing
require_once __DIR__ . '/config/database.php';

echo "<h2>Enhanced Sample Data Setup</h2>";

try {
    // Clear existing data for fresh start
    echo "<h3>Clearing existing data...</h3>";
    $conn->query("DELETE FROM asset_ledger WHERE 1=1");
    $conn->query("DELETE FROM asset WHERE 1=1");
    $conn->query("DELETE FROM employee WHERE 1=1");
    echo "<p>✅ Cleared existing data</p>";

    // Insert comprehensive sample assets with proper dates
    echo "<h3>Adding Enhanced Sample Assets...</h3>";

    $currentDate = date('Y-m-d');
    $assets = [
        // Hardware - Various ages and warranty statuses
        ['Dell Latitude 7420', 'hardware', 'laptop', '2024-01-15', 'DL7420-001', '', '', 24, 1200.00, 'in_use', 'good'],
        ['HP EliteBook 840', 'hardware', 'laptop', '2023-06-10', 'HP840-001', '', '', 36, 1400.00, 'available', 'excellent'],
        ['MacBook Pro 16"', 'hardware', 'laptop', '2022-03-20', 'MBP16-001', '', '', 24, 2500.00, 'in_use', 'good'],
        ['ThinkPad X1 Carbon', 'hardware', 'laptop', '2021-11-05', 'TP-X1-001', '', '', 36, 1800.00, 'retired', 'needs_repair'],
        
        ['HP LaserJet Pro', 'office_equipment', 'printer', '2023-08-15', 'HP-LJ-001', '', '', 12, 300.00, 'available', 'good'],
        ['Canon ImageClass', 'office_equipment', 'printer', '2022-01-30', 'CN-IC-001', '', '', 24, 450.00, 'in_use', 'good'],
        
        ['Dell OptiPlex 7090', 'hardware', 'desktop', '2023-12-01', 'DO7090-001', '', '', 36, 800.00, 'available', 'excellent'],
        ['iMac 24"', 'hardware', 'desktop', '2021-09-15', 'IMAC24-001', '', '', 12, 1299.00, 'disposed', 'damaged'],
        
        // Software - with license expiries
        ['Microsoft Office 365', 'software', 'productivity', '2024-01-01', '', 'MS365-BUS-001', '2025-01-01', 0, 150.00, 'active', 'excellent'],
        ['Adobe Creative Suite', 'software', 'design', '2023-07-01', '', 'ACS-2023-001', '2024-07-01', 0, 600.00, 'expired', 'excellent'],
        ['AutoCAD License', 'software', 'design', '2024-03-15', '', 'ACAD-2024-001', '2025-03-15', 0, 4000.00, 'active', 'excellent'],
        ['Slack Business Plan', 'software', 'communication', '2024-06-01', '', 'SLACK-BIZ-001', '2025-06-01', 0, 96.00, 'active', 'excellent'],
        
        // Office Equipment
        ['Standing Desk Pro', 'office_equipment', 'furniture', '2023-05-20', 'SD-PRO-001', '', '', 60, 400.00, 'in_use', 'good'],
        ['Herman Miller Chair', 'office_equipment', 'furniture', '2022-08-10', 'HM-CHAIR-001', '', '', 120, 1200.00, 'available', 'excellent'],
        ['Conference Table 12ft', 'office_equipment', 'furniture', '2021-04-15', 'CT-12FT-001', '', '', 240, 800.00, 'available', 'good'],
        
        // Network Equipment
        ['Cisco Catalyst Switch', 'hardware', 'networking', '2023-02-28', 'CISCO-CAT-001', '', '', 60, 2500.00, 'in_use', 'excellent'],
        ['Ubiquiti Access Point', 'hardware', 'networking', '2022-11-12', 'UBI-AP-001', '', '', 24, 150.00, 'available', 'good'],
        
        // Storage
        ['Synology NAS DS920+', 'hardware', 'storage', '2023-09-05', 'SYN-920-001', '', '', 24, 600.00, 'in_use', 'excellent'],
        ['WD External HDD 4TB', 'hardware', 'storage', '2021-12-20', 'WD-EXT-001', '', '', 36, 120.00, 'retired', 'good'],
        
        // Mobile Devices
        ['iPhone 14 Pro', 'hardware', 'mobile', '2023-10-10', 'IP14P-001', '', '', 12, 999.00, 'in_use', 'excellent'],
        ['Samsung Galaxy Tab', 'hardware', 'mobile', '2022-05-25', 'SGT-001', '', '', 24, 350.00, 'available', 'good']
    ];

    $stmt = $conn->prepare("INSERT INTO asset (name, category, subcategory, purchase_date, serial_number, license_key, license_expiry, warranty_period, unit_price, asset_status, asset_condition, notes, image_path, is_deleted) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '', '', 0)");

    foreach ($assets as $asset) {
        $stmt->bind_param("sssssssiiss", ...$asset);
        if ($stmt->execute()) {
            echo "<li>Added: {$asset[0]} ({$asset[1]}) - Purchase: {$asset[3]}, Warranty: {$asset[7]} months</li>";
        }
    }
    $stmt->close();
    echo "<p>✅ Enhanced assets added successfully!</p>";

    // Insert sample employees with diverse roles
    echo "<h3>Adding Sample Employees...</h3>";

    $employees = [
        ['john.doe', 'John', 'Doe', 'john.doe@company.com', '555-0101', '1990-05-15', 'Software Developer', 0],
        ['jane.smith', 'Jane', 'Smith', 'jane.smith@company.com', '555-0102', '1988-08-22', 'UI/UX Designer', 0],
        ['admin.user', 'Admin', 'User', 'admin@company.com', '555-0100', '1985-03-10', 'IT Manager', 1],
        ['mike.johnson', 'Mike', 'Johnson', 'mike.j@company.com', '555-0103', '1992-11-30', 'IT Support', 0],
        ['sarah.wilson', 'Sarah', 'Wilson', 'sarah.w@company.com', '555-0104', '1991-07-18', 'HR Manager', 0],
        ['david.brown', 'David', 'Brown', 'david.b@company.com', '555-0105', '1987-12-03', 'Project Manager', 0],
        ['lisa.garcia', 'Lisa', 'Garcia', 'lisa.g@company.com', '555-0106', '1993-09-14', 'Marketing Specialist', 0],
        ['tom.anderson', 'Tom', 'Anderson', 'tom.a@company.com', '555-0107', '1989-04-28', 'Sales Manager', 0],
        ['emma.davis', 'Emma', 'Davis', 'emma.d@company.com', '555-0108', '1994-01-12', 'Data Analyst', 0],
        ['alex.rodriguez', 'Alex', 'Rodriguez', 'alex.r@company.com', '555-0109', '1986-11-08', 'DevOps Engineer', 0]
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

    // Add comprehensive asset allocations
    echo "<h3>Adding Sample Asset Allocations...</h3>";

    // Get IDs for allocations
    $assetResult = $conn->query("SELECT asset_id, name FROM asset WHERE asset_status = 'in_use'");
    $empResult = $conn->query("SELECT emp_id, first_name, last_name FROM employee WHERE is_deleted = 0");

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

        // Create allocations with various dates for trend analysis
        $allocations = [
            [$assets[0]['asset_id'], $employees[0]['emp_id'], '2024-07-01', 'Development work'],
            [$assets[1]['asset_id'], $employees[1]['emp_id'], '2024-07-15', 'Design projects'],
            [$assets[2]['asset_id'], $employees[2]['emp_id'], '2024-08-01', 'Administrative tasks'],
        ];

        // Add more allocations if we have enough assets and employees
        if (count($assets) >= 4 && count($employees) >= 4) {
            $allocations = array_merge($allocations, [
                [$assets[3]['asset_id'], $employees[3]['emp_id'], '2024-06-10', 'Support tasks'],
                [$assets[0]['asset_id'], $employees[4]['emp_id'], '2024-05-20', 'Project work'],
                [$assets[1]['asset_id'], $employees[5]['emp_id'], '2024-04-15', 'Training'],
            ]);
        }

        foreach ($allocations as $alloc) {
            $stmt->bind_param("iiss", $alloc[0], $alloc[1], $alloc[2], $alloc[3]);
            if ($stmt->execute()) {
                echo "<li>Allocated {$assets[array_search($alloc[0], array_column($assets, 'asset_id'))]['name']} to employee ID {$alloc[1]}</li>";
            }
        }
        $stmt->close();
        echo "<p>✅ Sample allocations added successfully!</p>";
    }

    echo "<hr>";
    echo "<h3>📊 Dashboard Data Summary:</h3>";

    // Show comprehensive stats
    $queries = [
        'Total Assets' => "SELECT COUNT(*) as count FROM asset WHERE is_deleted = 0",
        'Assets with Warranty' => "SELECT COUNT(*) as count FROM asset WHERE is_deleted = 0 AND warranty_period > 0",
        'Assets 3+ Years Old' => "SELECT COUNT(*) as count FROM asset WHERE is_deleted = 0 AND purchase_date <= DATE_SUB(CURDATE(), INTERVAL 3 YEAR)",
        'Assets with Allocations' => "SELECT COUNT(DISTINCT asset_id) as count FROM asset_ledger WHERE is_deleted = 0",
        'Total Employees' => "SELECT COUNT(*) as count FROM employee WHERE is_deleted = 0",
        'Total Allocations' => "SELECT COUNT(*) as count FROM asset_ledger WHERE is_deleted = 0"
    ];

    echo "<ul>";
    foreach ($queries as $label => $query) {
        $result = $conn->query($query);
        $count = $result->fetch_assoc()['count'];
        echo "<li><strong>$label:</strong> $count</li>";
    }
    echo "</ul>";

    echo "<div style='margin-top: 20px; padding: 15px; background: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 8px;'>";
    echo "<h4>✅ Setup Complete!</h4>";
    echo "<p>Your dashboard now has comprehensive sample data including:</p>";
    echo "<ul>";
    echo "<li>📦 21 assets with proper purchase dates and warranty periods</li>";
    echo "<li>👥 10 employees across different departments</li>";
    echo "<li>📊 Multiple asset allocations for utilization tracking</li>";
    echo "<li>⚠️ Assets with expiring warranties</li>";
    echo "<li>📅 Assets older than 3 years for aging reports</li>";
    echo "</ul>";
    echo "</div>";

    echo "<p><a href='/asset_management/dashboard' style='display: inline-block; padding: 12px 24px; background: #3B82F6; color: white; text-decoration: none; border-radius: 8px; margin-top: 20px; font-weight: 600;'>🚀 View Enhanced Dashboard</a></p>";

} catch (Exception $e) {
    echo "<p style='color: red; padding: 10px; background: #fee; border: 1px solid #fcc; border-radius: 4px;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
