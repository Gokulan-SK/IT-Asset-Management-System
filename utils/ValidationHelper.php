<?php
// utils/ValidationHelper.php
require_once BASE_PATH . 'asset/helpers/AssetHelper.php';

class ValidationHelper
{

    public static function isAbsolutePath($path)
    {
        $pattern = "/^(?:[a-zA-Z]:\\\\|\/)[\w\s\-\.\/\\\\]+$/";
        return preg_match($pattern, $path);
    }

    public static function isPositiveInteger($value)
    {
        return $value >= 0 ? true : false;
    }

    public static function isAlphabeticString(string $name): bool
    {
        return (bool) preg_match('/^[a-zA-Z ]+$/', $name);
    }

    public static function isValidLength(string $value, int $minLength, int $maxLength): bool
    {
        return strlen($value) >= $minLength && strlen($value) <= $maxLength;
    }

    public static function isValidDate(string $dob): bool
    {
        // Validate DOB format as YYYY-MM-DD
        $format = 'Y-m-d';
        $d = DateTime::createFromFormat($format, $dob);
        return $d && $d->format($format) === $dob;
    }

    public static function isNumeric($value)
    {
        return preg_match("/^[0-9]+$/", $value);
    }
    public static function isValidBinary($value): bool
    {
        return $value === '0' || $value === '1';
    }
    public static function isValidPhone(string $phone): bool
    {
        return preg_match('/^[0-9]{10}$/', $phone);
    }

    public static function isUnique(mysqli $conn, string $table, string $field, $value, string $uniqueField = null, int $excludeId = null): bool
    {
        $valueType = is_numeric($value) ? 'i' : 's';
        $query = "SELECT count(*) as count FROM $table WHERE $field = ?" . ($excludeId !== null ? " AND $uniqueField != ?" : "");
        if ($excludeId !== null) {
            if (!$uniqueField) {
                error_log("ValidationHelper::isUnique() Error: uniqueField is required when excludeId is provided.");
                return false;
            }
            $query .= " AND `$uniqueField` != ?";
        }
        try {
            $stmt = $conn->prepare($query);
            if ($excludeId !== null) {
                $stmt->bind_param($valueType . 'i', $value, $excludeId);
            } else {
                $stmt->bind_param("$valueType", $value);
            }

            if (!$stmt) {
                error_log("ValidationHelper::isUnique() Error: " . $conn->error);
                return false;
            }
            $stmt->execute();
            if ($stmt->error) {
                error_log("ValidationHelper::isUnique() Error: " . $stmt->error);
                $stmt->close();
                return false;
            }
            $result = $stmt->get_result();
            $stmt->close();
            if ($result->fetch_assoc()["count"] > 0) {
                return false;
            }
            return true;
        } catch (Exception $e) {
            error_log("ValidationHelper::isUnique() Error:" . $e->getMessage());
            ;
            return false;
        }

    }


}
?>