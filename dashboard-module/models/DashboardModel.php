<?php
class DashboardModel
{
    // Asset Analytics
    public static function getAssetSummary(mysqli $conn): array
    {
        try {
            // First, let's see what status values actually exist
            $statusQuery = "SELECT DISTINCT asset_status FROM asset WHERE is_deleted = 0";
            $statusResult = $conn->query($statusQuery);
            $existingStatuses = [];
            if ($statusResult) {
                while ($row = $statusResult->fetch_assoc()) {
                    $existingStatuses[] = $row['asset_status'];
                }
            }

            $query = "SELECT 
                        COUNT(*) as total_assets,
                        SUM(CASE 
                            WHEN asset_status IN ('in_storage', 'active') THEN 1 
                            ELSE 0 
                        END) as available_assets,
                        SUM(CASE 
                            WHEN asset_status = 'in_use' THEN 1 
                            ELSE 0 
                        END) as in_use_assets,
                        SUM(CASE 
                            WHEN asset_status = 'under_repair' THEN 1 
                            ELSE 0 
                        END) as maintenance_assets,
                        SUM(CASE 
                            WHEN asset_status IN ('retired', 'disposed', 'expired', 'inactive', 'decommissioned') THEN 1 
                            ELSE 0 
                        END) as retired_assets
                      FROM asset 
                      WHERE is_deleted = 0";

            $result = $conn->query($query);
            if ($result) {
                $data = $result->fetch_assoc();
                // Add debug info
                $data['existing_statuses'] = $existingStatuses;
                return $data;
            }
            return [];
        } catch (Exception $e) {
            error_log("DashboardModel::getAssetSummary - Error: " . $e->getMessage());
            return [];
        }
    }

    public static function getAssetsByCategory(mysqli $conn): array
    {
        try {
            $query = "SELECT category, COUNT(*) as count 
                      FROM asset 
                      WHERE is_deleted = 0 
                      GROUP BY category 
                      ORDER BY count DESC";

            $result = $conn->query($query);
            $categories = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $categories[] = $row;
                }
            }
            return $categories;
        } catch (Exception $e) {
            error_log("DashboardModel::getAssetsByCategory - Error: " . $e->getMessage());
            return [];
        }
    }

    public static function getAssetStatusDistribution(mysqli $conn): array
    {
        try {
            $query = "SELECT 
                        asset_status,
                        COUNT(*) as count,
                        category
                      FROM asset 
                      WHERE is_deleted = 0 
                      GROUP BY asset_status, category
                      ORDER BY count DESC";

            $result = $conn->query($query);
            $statusData = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $statusData[] = $row;
                }
            }
            return $statusData;
        } catch (Exception $e) {
            error_log("DashboardModel::getAssetStatusDistribution - Error: " . $e->getMessage());
            return [];
        }
    }

    public static function getAssetsByCondition(mysqli $conn): array
    {
        try {
            $query = "SELECT asset_condition, COUNT(*) as count 
                      FROM asset 
                      WHERE is_deleted = 0 
                      GROUP BY asset_condition 
                      ORDER BY count DESC";

            $result = $conn->query($query);
            $conditions = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $conditions[] = $row;
                }
            }
            return $conditions;
        } catch (Exception $e) {
            error_log("DashboardModel::getAssetsByCondition - Error: " . $e->getMessage());
            return [];
        }
    }

    public static function getAssetValueSummary(mysqli $conn): array
    {
        try {
            $query = "SELECT 
                        SUM(unit_price) as total_value,
                        AVG(unit_price) as average_value,
                        MAX(unit_price) as highest_value,
                        MIN(unit_price) as lowest_value
                      FROM asset 
                      WHERE is_deleted = 0 AND unit_price > 0";

            $result = $conn->query($query);
            if ($result) {
                return $result->fetch_assoc();
            }
            return [];
        } catch (Exception $e) {
            error_log("DashboardModel::getAssetValueSummary - Error: " . $e->getMessage());
            return [];
        }
    }

    // Employee Analytics
    public static function getEmployeeSummary(mysqli $conn): array
    {
        try {
            $query = "SELECT 
                        COUNT(*) as total_employees,
                        SUM(CASE WHEN is_admin = 1 THEN 1 ELSE 0 END) as admin_employees
                      FROM employee 
                      WHERE is_deleted = 0";

            $result = $conn->query($query);
            if ($result) {
                return $result->fetch_assoc();
            }
            return [];
        } catch (Exception $e) {
            error_log("DashboardModel::getEmployeeSummary - Error: " . $e->getMessage());
            return [];
        }
    }

    public static function getEmployeesByDesignation(mysqli $conn): array
    {
        try {
            $query = "SELECT designation, COUNT(*) as count 
                      FROM employee 
                      WHERE is_deleted = 0 
                      GROUP BY designation 
                      ORDER BY count DESC";

            $result = $conn->query($query);
            $designations = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $designations[] = $row;
                }
            }
            return $designations;
        } catch (Exception $e) {
            error_log("DashboardModel::getEmployeesByDesignation - Error: " . $e->getMessage());
            return [];
        }
    }

    // Asset Ledger Analytics
    public static function getLedgerSummary(mysqli $conn): array
    {
        try {
            $query = "SELECT 
                        COUNT(*) as total_allocations,
                        SUM(CASE WHEN check_in_date IS NULL THEN 1 ELSE 0 END) as active_allocations,
                        SUM(CASE WHEN check_in_date IS NOT NULL THEN 1 ELSE 0 END) as returned_allocations
                      FROM asset_ledger";

            $result = $conn->query($query);
            if ($result) {
                return $result->fetch_assoc();
            }
            return [];
        } catch (Exception $e) {
            error_log("DashboardModel::getLedgerSummary - Error: " . $e->getMessage());
            return [];
        }
    }

    public static function getRecentActivity(mysqli $conn, int $limit = 10): array
    {
        try {
            $query = "SELECT 
                        al.check_out_date,
                        al.check_in_date,
                        a.name as asset_name,
                        CONCAT(e.first_name, ' ', e.last_name) as employee_name,
                        CASE 
                            WHEN al.check_in_date IS NULL THEN 'Checked Out'
                            ELSE 'Returned'
                        END as action
                      FROM asset_ledger al
                      JOIN asset a ON al.asset_id = a.asset_id
                      JOIN employee e ON al.emp_id = e.emp_id
                      WHERE a.is_deleted = 0 AND e.is_deleted = 0
                      ORDER BY 
                        CASE 
                            WHEN al.check_in_date IS NULL THEN al.check_out_date
                            ELSE al.check_in_date
                        END DESC
                      LIMIT ?";

            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $limit);
            $stmt->execute();
            $result = $stmt->get_result();

            $activities = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $activities[] = $row;
                }
            }
            $stmt->close();
            return $activities;
        } catch (Exception $e) {
            error_log("DashboardModel::getRecentActivity - Error: " . $e->getMessage());
            return [];
        }
    }

    public static function getAssetUtilization(mysqli $conn): array
    {
        try {
            $query = "SELECT 
                        a.name as asset_name,
                        a.category,
                        COUNT(al.asset_id) as allocation_count,
                        MAX(al.check_out_date) as last_allocated, a.unit_price
                      FROM asset a
                      LEFT JOIN asset_ledger al ON a.asset_id = al.asset_id
                      WHERE a.is_deleted = 0
                      GROUP BY a.asset_id, a.name, a.category
                      ORDER BY allocation_count DESC, last_allocated DESC
                      LIMIT 10";

            $result = $conn->query($query);
            $utilization = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $utilization[] = $row;
                }
            }
            return $utilization;
        } catch (Exception $e) {
            error_log("DashboardModel::getAssetUtilization - Error: " . $e->getMessage());
            return [];
        }
    }

    public static function getWarrantyExpiring(mysqli $conn, int $days = 30): array
    {
        try {
            $query = "SELECT 
                        name as asset_name,
                        category,
                        warranty_period,
                        purchase_date,
                        DATE_ADD(purchase_date, INTERVAL warranty_period MONTH) as warranty_expiry,
                        DATEDIFF(DATE_ADD(purchase_date, INTERVAL warranty_period MONTH), CURDATE()) as days_remaining
                      FROM asset 
                      WHERE is_deleted = 0 
                        AND warranty_period > 0 
                        AND DATE_ADD(purchase_date, INTERVAL warranty_period MONTH) > CURDATE()
                        AND DATEDIFF(DATE_ADD(purchase_date, INTERVAL warranty_period MONTH), CURDATE()) <= ?
                      ORDER BY days_remaining ASC";

            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $days);
            $stmt->execute();
            $result = $stmt->get_result();

            $expiring = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $expiring[] = $row;
                }
            }
            $stmt->close();
            return $expiring;
        } catch (Exception $e) {
            error_log("DashboardModel::getWarrantyExpiring - Error: " . $e->getMessage());
            return [];
        }
    }

    public static function getMonthlyAllocationTrend(mysqli $conn, int $months = 6): array
    {
        try {
            $query = "SELECT 
                        DATE_FORMAT(check_out_date, '%Y-%m') as month,
                        COUNT(*) as allocations
                      FROM asset_ledger 
                      WHERE check_out_date >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
                      GROUP BY DATE_FORMAT(check_out_date, '%Y-%m')
                      ORDER BY month ASC";

            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $months);
            $stmt->execute();
            $result = $stmt->get_result();

            $trends = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $trends[] = $row;
                }
            }
            $stmt->close();
            return $trends;
        } catch (Exception $e) {
            error_log("DashboardModel::getMonthlyAllocationTrend - Error: " . $e->getMessage());
            return [];
        }
    }

    // Asset Utilization Rate
    public static function getAssetUtilizationRate(mysqli $conn): array
    {
        try {
            $query = "SELECT 
                        (SELECT COUNT(*) FROM asset WHERE is_deleted = 0 AND asset_status = 'in_use') as total_assets,
                        (SELECT COUNT(*) FROM asset WHERE is_deleted = 0 AND asset_status = 'in_use') as assets_in_use,
                        ROUND(
                            (SELECT COUNT(*) FROM asset WHERE is_deleted = 0 AND asset_status = 'in_use') * 100.0 / 
                            GREATEST((SELECT COUNT(*) FROM asset WHERE is_deleted = 0), 1), 1
                        ) as utilization_percentage";

            $result = $conn->query($query);
            if ($result) {
                return $result->fetch_assoc();
            }
            return ['total_assets' => 0, 'assets_in_use' => 0, 'utilization_percentage' => 0];
        } catch (Exception $e) {
            error_log("DashboardModel::getAssetUtilizationRate - Error: " . $e->getMessage());
            return ['total_assets' => 0, 'assets_in_use' => 0, 'utilization_percentage' => 0];
        }
    }

    // Aging Assets (older than specified years)
    public static function getAgingAssets(mysqli $conn, int $years = 3): array
    {
        try {
            $query = "SELECT 
                        name as asset_name,
                        category,
                        purchase_date,
                        DATEDIFF(CURDATE(), purchase_date) as age_days,
                        ROUND(DATEDIFF(CURDATE(), purchase_date) / 365.25, 1) as age_years,
                        asset_status,
                        unit_price
                      FROM asset 
                      WHERE is_deleted = 0 
                        AND purchase_date IS NOT NULL
                        AND DATEDIFF(CURDATE(), purchase_date) >= (? * 365)
                      ORDER BY age_days DESC
                      LIMIT 20";

            $stmt = $conn->prepare($query);
            $yearsInDays = $years * 365;
            $stmt->bind_param("i", $yearsInDays);
            $stmt->execute();
            $result = $stmt->get_result();

            $aging = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $aging[] = $row;
                }
            }
            $stmt->close();
            return $aging;
        } catch (Exception $e) {
            error_log("DashboardModel::getAgingAssets - Error: " . $e->getMessage());
            return [];
        }
    }

    // Top Asset Categories by Usage (allocation frequency)
    public static function getTopCategoriesByUsage(mysqli $conn): array
    {
        try {
            $query = "SELECT 
                        a.category,
                        COUNT(al.asset_id) as total_allocations,
                        COUNT(DISTINCT a.asset_id) as assets_in_category,
                        ROUND(COUNT(al.asset_id) / COUNT(DISTINCT a.asset_id), 1) as avg_allocations_per_asset
                      FROM asset a
                      LEFT JOIN asset_ledger al ON a.asset_id = al.asset_id
                      WHERE a.is_deleted = 0
                      GROUP BY a.category
                      HAVING COUNT(al.asset_id) > 0
                      ORDER BY total_allocations DESC, avg_allocations_per_asset DESC
                      LIMIT 10";

            $result = $conn->query($query);
            $categories = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $categories[] = $row;
                }
            }
            return $categories;
        } catch (Exception $e) {
            error_log("DashboardModel::getTopCategoriesByUsage - Error: " . $e->getMessage());
            return [];
        }
    }

    // Asset Value Distribution by Status
    public static function getAssetValueByStatus(mysqli $conn): array
    {
        try {
            $query = "SELECT 
                        asset_status,
                        COUNT(*) as asset_count,
                        SUM(unit_price) as total_value,
                        AVG(unit_price) as avg_value
                      FROM asset 
                      WHERE is_deleted = 0 AND unit_price > 0
                      GROUP BY asset_status
                      ORDER BY total_value DESC";

            $result = $conn->query($query);
            $valueByStatus = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $valueByStatus[] = $row;
                }
            }
            return $valueByStatus;
        } catch (Exception $e) {
            error_log("DashboardModel::getAssetValueByStatus - Error: " . $e->getMessage());
            return [];
        }
    }
}
?>