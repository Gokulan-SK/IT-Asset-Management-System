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
}

?>