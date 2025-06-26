<?php

class AssetModel
{
    public static function create(mysqli $conn, array $data): bool
    {
        $query = "insert into asset (
            name, category, subcategory, purchase_date, serial_number, license_key, license_expiry, warranty_period, unit_price, asset_status, asset_condition, notes, image_path
        ) values (
         ?,?,?,?,?,?,?,?,?,?,?,?,?
        )";

        $stmt = $conn->prepare($query);

        if (!$stmt) {
            error_log("AssetModel::create Error:" . mysqli_error($conn));
            return false;
        }

        $stmt->bind_param(
            "sssssssidssss",
            $data["name"],
            $data["category"],
            $data["subcategory"],
            $data["purchase-date"],
            $data["serial-number"],
            $data["license-key"],
            $data["license-expiry"],
            $data["warranty-period"],
            $data["unit-price"],
            $data["status"],
            $data["condition"],
            $data["notes"],
            $data["image"]
        );
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    public static function getAssetById(mysqli $conn, $asset_id)
    {
        try {

            $query = "SELECT name, category,subcategory, purchase_date, serial_number, license_key, license_expiry, warranty_period, unit_price, asset_status , asset_condition, notes, image_path from asset where asset_id = ?";
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                error_log("AssetModal::getAssetById Error:" . mysqli_error($conn));
                return false;
            }
            $stmt->bind_param("i", $asset_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $row = $result->fetch_assoc()) {
                $stmt->close();
                return $row;
            } else {
                $stmt->close();
                return null;
            }
        } catch (Exception $e) {
            error_log("AssetModel::getAssetById Error:" . $e->getMessage());
            return null;
        }
    }

    public static function getAssetCount(mysqli $conn): int
    {
        try {
            $query = "select count(asset_id) as total from asset";
            $result = $conn->query($query);
            if ($result && $row = $result->fetch_assoc()) {
                return (int) $row["total"];
            }
            return 0;
        } catch (Exception $e) {
            error_log("AssetModel::getAssetCount() Error:" . $e->getMessage());
            return 0;
        }
    }

    public static function getPaginatedAssetList(mysqli $conn, int $limit, int $offset): array
    {
        try {
            $query = "SELECT asset_id, name, category, subcategory, asset_status FROM asset ORDER BY created_at DESC LIMIT ? OFFSET ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $limit, $offset);
            $stmt->execute();
            $result = $stmt->get_result();

            $assets = [];

            while ($row = $result->fetch_assoc()) {
                $assets[] = $row;
            }
            $stmt->close();

            return $assets;

        } catch (Exception $e) {
            error_log("AssetModel::getPaginatedAssetList Error:" . $e->getMessage());
            return [];
        }
    }

    public static function deleteAssetById(mysqli $conn, int $assetId): bool
    {
        try {
            $query = "DELETE FROM asset WHERE asset_id = ?";

            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $assetId);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                $stmt->close();
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log("AssetModel::deleteAsset Error:" . $e->getMessage());
            return false;
        }
    }
}

?>