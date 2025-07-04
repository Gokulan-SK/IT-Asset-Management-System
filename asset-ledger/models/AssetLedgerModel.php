<?php

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
                e.first_name AS first_name,
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
            $query2 = "UPDATE asset SET asset_status = 'in_storage' 
                   WHERE asset_id = (SELECT asset_id FROM asset_ledger WHERE ledger_id = ?)";
            $stmt2 = $conn->prepare($query2);
            $stmt2->bind_param("i", $ledgerId);
            $stmt2->execute();
            $stmt2->close();

            return true;
        } catch (Exception $e) {
            error_log("AssetLedgerModel::checkin Error: " . $e->getMessage());
            return false;
        }
    }


}

?>