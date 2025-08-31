<?php
require_once __DIR__ . '/config/database.php';

echo "<h3>Table Data Debug</h3>";

try {
    // Check warranty expiring assets
    echo "<h4>1. Warranty Expiring Assets Debug:</h4>";
    $query = "SELECT 
                name as asset_name,
                category,
                warranty_period,
                purchase_date,
                DATE_ADD(purchase_date, INTERVAL warranty_period MONTH) as warranty_expiry,
                DATEDIFF(DATE_ADD(purchase_date, INTERVAL warranty_period MONTH), CURDATE()) as days_remaining
              FROM asset 
              WHERE is_deleted = 0 
                AND warranty_period IS NOT NULL
                AND warranty_period > 0
                AND purchase_date IS NOT NULL
              ORDER BY warranty_period DESC
              LIMIT 10";
    
    $result = $conn->query($query);
    if ($result) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Asset Name</th><th>Category</th><th>Warranty Period</th><th>Purchase Date</th><th>Warranty Expiry</th><th>Days Remaining</th></tr>";
        $count = 0;
        while ($row = $result->fetch_assoc()) {
            $count++;
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['asset_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['category']) . "</td>";
            echo "<td>" . $row['warranty_period'] . " months</td>";
            echo "<td>" . $row['purchase_date'] . "</td>";
            echo "<td>" . $row['warranty_expiry'] . "</td>";
            echo "<td>" . $row['days_remaining'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<p>Total assets with warranty: $count</p>";
    }
    
    // Check aging assets
    echo "<h4>2. Aging Assets Debug:</h4>";
    $query = "SELECT 
                name as asset_name,
                category,
                purchase_date,
                DATEDIFF(CURDATE(), purchase_date) as age_days,
                ROUND(DATEDIFF(CURDATE(), purchase_date) / 365.25, 1) as age_years,
                asset_status
              FROM asset 
              WHERE is_deleted = 0 
                AND purchase_date IS NOT NULL
              ORDER BY age_days DESC
              LIMIT 10";
    
    $result = $conn->query($query);
    if ($result) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Asset Name</th><th>Category</th><th>Purchase Date</th><th>Age (Days)</th><th>Age (Years)</th><th>Status</th></tr>";
        $count = 0;
        while ($row = $result->fetch_assoc()) {
            $count++;
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['asset_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['category']) . "</td>";
            echo "<td>" . $row['purchase_date'] . "</td>";
            echo "<td>" . $row['age_days'] . "</td>";
            echo "<td>" . $row['age_years'] . "</td>";
            echo "<td>" . htmlspecialchars($row['asset_status']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<p>Total assets with purchase date: $count</p>";
    }
    
    // Check asset utilization
    echo "<h4>3. Asset Utilization Debug:</h4>";
    $query = "SELECT 
                a.name as asset_name,
                a.category,
                COUNT(al.asset_id) as allocation_count,
                MAX(al.check_out_date) as last_allocated
              FROM asset a
              LEFT JOIN asset_ledger al ON a.asset_id = al.asset_id AND al.is_deleted = 0
              WHERE a.is_deleted = 0
              GROUP BY a.asset_id, a.name, a.category
              ORDER BY allocation_count DESC
              LIMIT 10";
    
    $result = $conn->query($query);
    if ($result) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Asset Name</th><th>Category</th><th>Allocation Count</th><th>Last Allocated</th></tr>";
        $count = 0;
        while ($row = $result->fetch_assoc()) {
            $count++;
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['asset_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['category']) . "</td>";
            echo "<td>" . $row['allocation_count'] . "</td>";
            echo "<td>" . ($row['last_allocated'] ?: 'Never') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<p>Total assets checked: $count</p>";
    }
    
    // Check asset ledger data
    echo "<h4>4. Asset Ledger Data:</h4>";
    $query = "SELECT COUNT(*) as total_ledger_entries FROM asset_ledger WHERE is_deleted = 0";
    $result = $conn->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
        echo "<p>Total ledger entries: " . $row['total_ledger_entries'] . "</p>";
    }
    
    // Check for missing data fields
    echo "<h4>5. Data Field Analysis:</h4>";
    $query = "SELECT 
                COUNT(*) as total_assets,
                COUNT(purchase_date) as assets_with_purchase_date,
                COUNT(warranty_period) as assets_with_warranty,
                AVG(warranty_period) as avg_warranty_months
              FROM asset WHERE is_deleted = 0";
    $result = $conn->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
        echo "<p>Total assets: " . $row['total_assets'] . "</p>";
        echo "<p>Assets with purchase date: " . $row['assets_with_purchase_date'] . "</p>";
        echo "<p>Assets with warranty: " . $row['assets_with_warranty'] . "</p>";
        echo "<p>Average warranty period: " . $row['avg_warranty_months'] . " months</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
?>
