<?php
require_once BASE_PATH . "asset/models/AssetModel.php";

class AssetLedgerModel
{
    public static function checkout($conn, $assetId, $empId, $assignedBy, $checkoutDate, $comments)
    {
        try {


            $query = "INSERT INTO asset_ledger (asset_id, emp_id,assigned_by, check_out_date, comments) values( ?,?,?,?,?)";

            $stmt = $conn->prepare($query);

            if (!$stmt) {
                error_log("AssetLedgerModel::checkout - Prepare statement failed: " . $conn->error);
                return false;
            }

            $stmt->bind_param("iiiss", $assetId, $empId, $assignedBy, $checkoutDate, $comments);

            $stmt->execute();

            if ($stmt->error) {
                error_log("AssetLedgerModel::checkout - execution failed: " . $conn->error);
                $stmt->close();
                return false;
            }
            $stmt->close();
            return true;
        } catch (Exception $e) {
            error_log("AssetLedgerModel::checkout - Exception: " . $e->getMessage());
            return false;
        }
    }
    public static function getLedgerCount(mysqli $conn): int
    {
        try {
            $query = "SELECT COUNT(ledger_id) AS total FROM asset_ledger";
            $result = $conn->query($query);
            if ($result && $row = $result->fetch_assoc()) {
                return (int) $row["total"];
            }
            return 0;
        } catch (Exception $e) {
            error_log("AssetLedgerModel::getLedgerCount() Error: " . $e->getMessage());
            return 0;
        }
    }

    public static function getPaginatedLedgerList(mysqli $conn, int $limit, int $offset): array
    {
        try {
            $query = "
            SELECT 
                al.ledger_id,
                al.asset_id,
                a.name,
                al.emp_id,
                CASE 
                    WHEN e.is_deleted = 1 THEN CONCAT(e.first_name, ' (Deleted)')
                    ELSE e.first_name
                END AS first_name,
                al.check_out_date,
                al.check_in_date
            FROM asset_ledger al
            JOIN asset a ON al.asset_id = a.asset_id
            JOIN employee e ON al.emp_id = e.emp_id
            ORDER BY al.check_out_date DESC
            LIMIT ? OFFSET ?";

            $stmt = $conn->prepare($query);
            if (!$stmt) {
                error_log("AssetLedgerModel::getPaginatedLedgerList - Prepare failed: " . $conn->error);
                return [];
            }

            $stmt->bind_param("ii", $limit, $offset);
            $stmt->execute();
            $result = $stmt->get_result();

            $ledgerEntries = [];
            while ($row = $result->fetch_assoc()) {
                $ledgerEntries[] = $row;
            }

            $stmt->close();
            return $ledgerEntries;

        } catch (Exception $e) {
            error_log("AssetLedgerModel::getPaginatedLedgerList Error: " . $e->getMessage());
            return [];
        }
    }

    public static function getLedgerById(mysqli $conn, int $ledgerId): ?array
    {
        try {
            $query = "
            SELECT 
                al.ledger_id,
                al.asset_id,
                a.name AS asset_name,
                al.emp_id,
                CONCAT(e.first_name, ' ', e.last_name) AS employee_name,
                al.check_out_date,
                al.check_in_date,
                al.comments
            FROM asset_ledger al
            JOIN asset a ON al.asset_id = a.asset_id
            JOIN employee e ON al.emp_id = e.emp_id
            WHERE al.ledger_id = ?
        ";

            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $ledgerId);
            $stmt->execute();
            $result = $stmt->get_result();
            $ledger = $result->fetch_assoc();

            $stmt->close();

            return $ledger ?: null;
        } catch (Exception $e) {
            error_log("AssetLedgerModel::getLedgerById Error: " . $e->getMessage());
            return null;
        }
    }


    public static function checkin(mysqli $conn, int $ledgerId, string $checkInDate, string $comments): bool
    {
        try {
            // 1. Update asset_ledger
            $query = "UPDATE asset_ledger SET check_in_date = ?, comments = ?, updated_at = CURRENT_TIMESTAMP() WHERE ledger_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssi", $checkInDate, $comments, $ledgerId);
            $stmt->execute();
            $stmt->close();

            // 2. Set asset status back to 'in_storage'
            $query = "select al.asset_id,a.category from asset_ledger al join asset a on al.asset_id = a.asset_id where ledger_id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $ledgerId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            $assetId = $row['asset_id'];
            $category = $row['category'];
            $result = AssetModel::updateAssetStatus($conn, $assetId, $category === 'software' ? 'active' : 'in_storage');
            echo "<script>console.log(" . json_encode($result) . ")</script>";

            return true;
        } catch (Exception $e) {
            error_log("AssetLedgerModel::checkin Error: " . $e->getMessage());
            return false;
        }
    }

    // Enhanced methods for search, filter, and export
    public static function getLedgerCountWithFilters(mysqli $conn, string $search = '', string $statusFilter = ''): int
    {
        try {
            $query = "SELECT COUNT(al.ledger_id) AS total 
                     FROM asset_ledger al
                     JOIN asset a ON al.asset_id = a.asset_id
                     JOIN employee e ON al.emp_id = e.emp_id
                     WHERE 1=1";

            $params = [];
            $types = '';

            if (!empty($search)) {
                $query .= " AND (al.asset_id LIKE ? OR al.emp_id LIKE ? OR a.name LIKE ? OR e.first_name LIKE ? OR e.last_name LIKE ?)";
                $searchTerm = "%$search%";
                $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
                $types .= 'sssss';
            }

            if (!empty($statusFilter)) {
                if ($statusFilter === 'assigned') {
                    $query .= " AND al.check_in_date IS NULL";
                } elseif ($statusFilter === 'available') {
                    $query .= " AND al.check_in_date IS NOT NULL";
                }
            }

            $stmt = $conn->prepare($query);
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $row = $result->fetch_assoc()) {
                return (int) $row["total"];
            }
            return 0;
        } catch (Exception $e) {
            error_log("AssetLedgerModel::getLedgerCountWithFilters() Error: " . $e->getMessage());
            return 0;
        }
    }

    public static function getPaginatedLedgerListWithFilters(mysqli $conn, int $limit, int $offset, string $search = '', string $statusFilter = '', string $sort = 'ledger_id', string $order = 'ASC'): array
    {
        try {
            // Validate sort column
            $allowedSortColumns = ['ledger_id', 'asset_id', 'name', 'emp_id', 'first_name', 'check_out_date', 'check_in_date'];
            if (!in_array($sort, $allowedSortColumns)) {
                $sort = 'ledger_id';
            }

            // Validate order
            $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';

            $query = "SELECT 
                        al.ledger_id,
                        al.asset_id,
                        a.name,
                        al.emp_id,
                        e.first_name,
                        e.last_name,
                        al.check_out_date,
                        al.check_in_date,
                        al.comments
                      FROM asset_ledger al
                      JOIN asset a ON al.asset_id = a.asset_id
                      JOIN employee e ON al.emp_id = e.emp_id
                      WHERE 1=1";

            $params = [];
            $types = '';

            if (!empty($search)) {
                $query .= " AND (al.asset_id LIKE ? OR al.emp_id LIKE ? OR a.name LIKE ? OR e.first_name LIKE ? OR e.last_name LIKE ?)";
                $searchTerm = "%$search%";
                $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
                $types .= 'sssss';
            }

            if (!empty($statusFilter)) {
                if ($statusFilter === 'assigned') {
                    $query .= " AND al.check_in_date IS NULL";
                } elseif ($statusFilter === 'available') {
                    $query .= " AND al.check_in_date IS NOT NULL";
                }
            }

            $query .= " ORDER BY $sort $order LIMIT ? OFFSET ?";
            $params = array_merge($params, [$limit, $offset]);
            $types .= 'ii';

            $stmt = $conn->prepare($query);
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();

            $ledgerEntries = [];
            while ($row = $result->fetch_assoc()) {
                $ledgerEntries[] = $row;
            }

            $stmt->close();
            return $ledgerEntries;

        } catch (Exception $e) {
            error_log("AssetLedgerModel::getPaginatedLedgerListWithFilters Error: " . $e->getMessage());
            return [];
        }
    }

    public static function exportLedgers(mysqli $conn, string $search = '', string $statusFilter = ''): array
    {
        try {
            $query = "SELECT 
                        al.ledger_id,
                        al.asset_id,
                        a.name as asset_name,
                        al.emp_id,
                        CONCAT(e.first_name, ' ', e.last_name) as employee_name,
                        al.check_out_date,
                        al.check_in_date,
                        al.comments,
                        CASE 
                            WHEN al.check_in_date IS NULL THEN 'Assigned'
                            ELSE 'Returned'
                        END as status
                      FROM asset_ledger al
                      JOIN asset a ON al.asset_id = a.asset_id
                      JOIN employee e ON al.emp_id = e.emp_id
                      WHERE 1=1";

            $params = [];
            $types = '';

            if (!empty($search)) {
                $query .= " AND (al.asset_id LIKE ? OR al.emp_id LIKE ? OR a.name LIKE ? OR e.first_name LIKE ? OR e.last_name LIKE ?)";
                $searchTerm = "%$search%";
                $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
                $types .= 'sssss';
            }

            if (!empty($statusFilter)) {
                if ($statusFilter === 'assigned') {
                    $query .= " AND al.check_in_date IS NULL";
                } elseif ($statusFilter === 'available') {
                    $query .= " AND al.check_in_date IS NOT NULL";
                }
            }

            $query .= " ORDER BY al.ledger_id DESC";

            $stmt = $conn->prepare($query);
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();

            $ledgerEntries = [];
            while ($row = $result->fetch_assoc()) {
                $ledgerEntries[] = $row;
            }

            $stmt->close();
            return $ledgerEntries;

        } catch (Exception $e) {
            error_log("AssetLedgerModel::exportLedgers Error: " . $e->getMessage());
            return [];
        }
    }


}

?>