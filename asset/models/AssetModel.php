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
}

?>