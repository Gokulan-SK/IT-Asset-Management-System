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
        try {
            $valueType = is_numeric($value) ? 'i' : 's';
            $query = "SELECT count(*) as count FROM $table WHERE $field = ?";
            
            // Add soft deletion check for employee table
            if ($table === 'employee') {
                $query .= " AND is_deleted = 0";
            }
            
            if ($excludeId !== null && $uniqueField !== null) {
                $query .= " AND `$uniqueField` != ?";
            }
            
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                error_log("ValidationHelper::isUnique() Error: " . $conn->error);
                return false;
            }
            
            if ($excludeId !== null && $uniqueField !== null) {
                $stmt->bind_param($valueType . 'i', $value, $excludeId);
            } else {
                $stmt->bind_param($valueType, $value);
            }
            
            $stmt->execute();
            if ($stmt->error) {
                error_log("ValidationHelper::isUnique() Error: " . $stmt->error);
                $stmt->close();
                return false;
            }
            
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            
            return $row["count"] == 0;
        } catch (Exception $e) {
            error_log("ValidationHelper::isUnique() Error:" . $e->getMessage());
            return false;
        }
    }


}
?>