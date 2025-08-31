<?php
require_once __DIR__ . '/config/database.php';

echo "<h3>Asset Status Debug</h3>";

try {
    // Check what status values exist in the database
    $query = "SELECT asset_status, COUNT(*) as count FROM asset WHERE is_deleted = 0 GROUP BY asset_status ORDER BY count DESC";
    $result = $conn->query($query);
    
    echo "<h4>Current Asset Status Values in Database:</h4>";
    if ($result) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Status</th><th>Count</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . htmlspecialchars($row['asset_status']) . "</td><td>" . $row['count'] . "</td></tr>";
        }
        echo "</table>";
    }
    
    // Check categories
    echo "<h4>Current Asset Categories in Database:</h4>";
    $query = "SELECT category, COUNT(*) as count FROM asset WHERE is_deleted = 0 GROUP BY category ORDER BY count DESC";
    $result = $conn->query($query);
    
    if ($result) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Category</th><th>Count</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . htmlspecialchars($row['category']) . "</td><td>" . $row['count'] . "</td></tr>";
        }
        echo "</table>";
    }
    
    // Show asset conditions
    echo "<h4>Current Asset Conditions in Database:</h4>";
    $query = "SELECT asset_condition, COUNT(*) as count FROM asset WHERE is_deleted = 0 GROUP BY asset_condition ORDER BY count DESC";
    $result = $conn->query($query);
    
    if ($result) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Condition</th><th>Count</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . htmlspecialchars($row['asset_condition']) . "</td><td>" . $row['count'] . "</td></tr>";
        }
        echo "</table>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
?>
