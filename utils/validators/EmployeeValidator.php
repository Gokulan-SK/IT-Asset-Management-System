<?php
//utils/validators/EmployeeValidator.php

require_once BASE_PATH . 'utils/ValidationHelper.php';

class EmployeeValidator
{
    public static function isValidDesignation(string $designation): bool
    {
        return preg_match('/^[a-zA-Z0-9 ]+$/', $designation);
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

    public static function isValidUsername(string $username): bool
    {
        return preg_match('/^[a-zA-Z0-9_]+$/', $username);
    }

    public static function isUsernameTaken(mysqli $conn, string $username, int $excludeId = null): bool
    {
        $query = "SELECT username FROM employee WHERE username = ?" . ($excludeId !== null ? " AND emp_id != ?" : "");
        $stmt = $conn->prepare($query);

        if ($excludeId !== null) {
            $stmt->bind_param("si", $username, $excludeId);
        } else {
            $stmt->bind_param("s", $username);
        }

        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    public static function isEmailTaken(mysqli $conn, string $email, int $excludeId = null): bool
    {
        $query = "SELECT emp_id FROM employee WHERE email = ?" . ($excludeId !== null ? " AND emp_id != ?" : "");
        $stmt = $conn->prepare($query);

        if ($excludeId !== null) {
            $stmt->bind_param("si", $email, $excludeId);
        } else {
            $stmt->bind_param("s", $email);
        }

        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    public static function isPhoneTaken(mysqli $conn, string $phone, int $excludeId = null): bool
    {
        $query = "SELECT emp_id FROM employee WHERE phone = ?" . ($excludeId !== null ? " AND emp_id != ?" : "");
        $stmt = $conn->prepare($query);

        if ($excludeId !== null) {
            $stmt->bind_param("si", $phone, $excludeId);
        } else {
            $stmt->bind_param("s", $phone);
        }

        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    public static function validateForCreate(mysqli $conn, array $data): array
    {
        $errors = [];
        $data = array_map('trim', $data);

        if (empty($data['username'])) {
            $errors['usernameError'] = "Username is required.";
        } elseif (!self::isValidUsername($data['username'])) {
            $errors['usernameError'] = "Username can only contain letters, numbers, and underscores.";
        } elseif (self::isUsernameTaken($conn, $data['username'])) {
            $errors['usernameError'] = "Username already exists.";
        }

        if (empty($data['first-name']) || !ValidationHelper::isAlphabeticString($data['first-name'])) {
            $errors['firstNameError'] = "First name is required and can only contain letters.";
        }

        if (empty($data['last-name']) || !ValidationHelper::isAlphabeticString($data['last-name'])) {
            $errors['lastNameError'] = "Last name is required and can only contain letters.";
        }

        if (empty($data['email'])) {
            $errors['emailError'] = "Email is required.";
        } elseif (!self::isValidEmail($data['email'])) {
            $errors['emailError'] = "Invalid email format.";
        } elseif (self::isEmailTaken($conn, $data['email'])) {
            $errors['emailError'] = "Email already exists.";
        }

        if (empty($data['phone']) || !self::isValidPhone($data['phone'])) {
            $errors['phoneError'] = "Phone number is required and must be 10 digits.";
        } elseif (self::isPhoneTaken($conn, $data['phone'])) {
            $errors['phoneError'] = "Phone number already exists.";
        }

        if (empty($data['password']) || empty($data['confirm-password'])) {
            $errors['passwordError'] = "Password and Confirm Password are required.";
        } elseif ($data['password'] !== $data['confirm-password']) {
            $errors['passwordError'] = "Passwords do not match.";
        } elseif (!self::isValidPassword($data['password'])) {
            $errors['passwordError'] = "Password must meet complexity requirements.";
        }

        if (empty($data['dob']) || !ValidationHelper::isValidDate($data['dob'])) {
            $errors['dobError'] = "Date of Birth is required and must be valid.";
        }

        if (empty($data['designation']) || !self::isValidDesignation($data['designation'])) {
            $errors['designationError'] = "Designation is required and must be valid.";
        }

        if (!isset($data['is-admin']) || !ValidationHelper::isValidBinary($data['is-admin'])) {
            $errors['is-adminError'] = "Is Admin field is required and must be 0 or 1.";
        }

        return $errors;
    }

    public static function validateForUpdate(mysqli $conn, array $data, int $emp_id): array
    {
        $errors = [];
        $data = array_map('trim', $data);

        if (!empty($data['username']) && (!self::isValidUsername($data['username']) || self::isUsernameTaken($conn, $data['username'], $emp_id))) {
            $errors['usernameError'] = "Invalid or duplicate username.";
        }

        if (!empty($data['first-name']) && !ValidationHelper::isAlphabeticString($data['first-name'])) {
            $errors['firstNameError'] = "First name can only contain letters.";
        }

        if (!empty($data['last-name']) && !ValidationHelper::isAlphabeticString($data['last-name'])) {
            $errors['lastNameError'] = "Last name can only contain letters.";
        }

        if (!empty($data['email'])) {
            if (!self::isValidEmail($data['email'])) {
                $errors['emailError'] = "Invalid email format.";
            } elseif (self::isEmailTaken($conn, $data['email'], $emp_id)) {
                $errors['emailError'] = "Email already exists.";
            }
        }

        if ((!empty($data['password']) && (!empty($data['confirm-password']))) && ($data['password'] !== $data['confirm-password'])) {
            $errors['passwordError'] = "Passwords do not match.";
        } elseif (!empty($data['password']) && !self::isValidPassword($data['password'])) {
            $errors['passwordError'] = "Password must meet complexity requirements.";
        }

        if (!empty($data['phone'])) {
            if (!self::isValidPhone($data['phone'])) {
                $errors['phoneError'] = "Invalid phone number format.";
            } elseif (self::isPhoneTaken($conn, $data['phone'], $emp_id)) {
                $errors['phoneError'] = "Phone number already exists.";
            }
        }

        if (!empty($data['dob']) && !ValidationHelper::isValidDate($data['dob'])) {
            $errors['dobError'] = "Invalid date format.";
        }

        if (!empty($data['designation']) && !self::isValidDesignation($data['designation'])) {
            $errors['designationError'] = "Invalid designation format.";
        }

        if (isset($data['is-admin']) && !ValidationHelper::isValidBinary($data['is-admin'])) {
            $errors['is-adminError'] = "Is Admin must be either 0 or 1.";
        }

        return $errors;
    }
}
