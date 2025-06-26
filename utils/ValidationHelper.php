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


}
?>