<?php

class Helpers
{
    public static function isValidUsername(string $username): bool
    {
        return preg_match('/^[a-zA-Z0-9_]+$/', $username);
    }

    public static function isAlphabeticString(string $name): bool
    {
        return (bool) preg_match('/^[a-zA-Z ]+$/', $name);
    }

    public static function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function isValidPhone(string $phone): bool
    {
        return preg_match('/^[0-9]{10}$/', $phone);
    }

    public static function isValidPassword(string $password): bool
    {
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).{8,}$/';
        return preg_match($pattern, $password);
    }

    public static function isValidDate(string $dob): bool
    {
        // Validate DOB format as YYYY-MM-DD
        $format = 'Y-m-d';
        $d = DateTime::createFromFormat($format, $dob);
        return $d && $d->format($format) === $dob;
    }

    public static function isValidDesignation(string $designation): bool
    {
        return preg_match('/^[a-zA-Z0-9 ]+$/', $designation);
    }

    public static function isValidIsAdmin($value): bool
    {
        return $value === '0' || $value === '1';
    }

    public static function isNumeric($value)
    {
        return preg_match("/^[0-9]+$/", $value);
    }

}
?>