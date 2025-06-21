<?php
require_once BASE_PATH . 'utils/helpers.php';


class ValidationManager
{
    public static function isUsernameTaken(mysqli $conn, string $username): bool
    {
        $stmt = $conn->prepare("SELECT emp_id FROM employee WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    public static function isEmailTaken(mysqli $conn, string $email): bool
    {
        $stmt = $conn->prepare("SELECT emp_id FROM employee WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    public static function isPhoneTaken(mysqli $conn, string $phone): bool
    {
        $stmt = $conn->prepare("SELECT emp_id FROM employee WHERE phone = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }
    public static function validateEmployeeData(mysqli $conn, array $data)
    {
        $errors = [];
        $data = array_map('trim', $data);
        $username = $data['username'] ?? null;
        $firstName = $data["first-name"] ?? null;
        $lastName = $data["last-name"] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
        $confirmPassword = $data['confirm-password'] ?? null;
        $phone = $data['phone'] ?? null;
        $dob = $data['dob'] ?? null;
        $designation = $data['designation'] ?? null;
        $isAdmin = $data['is-admin'] ?? null;




        // Validate username
        if (empty($username)) {
            $errors['usernameError'] = "Username is required.";
        } elseif (!Helpers::isValidUsername($username)) {
            $errors['usernameError'] = "Username can only contain letters, numbers, and underscores.";
        } elseif (ValidationManager::isUsernameTaken($conn, $username)) {
            $errors['usernameError'] = "Username already exists.";
        }




        //validate first name

        if (empty($firstName)) {
            $errors['firstNameError'] = "First name is required.";
        } elseif (!Helpers::isValidName($firstName)) {
            $errors['firstNameError'] = 'First name can only contain letters.';
        }

        //validate last name
        if (empty($lastName)) {
            $errors['lastNameError'] = 'Last name is required.';
        } elseif (!Helpers::isValidName($lastName)) {
            $errors['lastNameError'] = 'Last name can only contain letters.';
        }

        //validate email
        if (empty($email)) {
            $errors['emailError'] = "Email is required.";
        } elseif (!Helpers::isValidEmail($email)) {
            $errors['emailError'] = "Invalid email format.";
        } else {
            $result = ValidationManager::isEmailTaken($conn, $email);
            if ($result) {
                $errors['emailError'] = 'Email already exists. Please enter a different email.';
            }
        }

        //validate phone
        if (empty($phone)) {
            $errors['phoneError'] = 'Phone number is required.';
        } elseif (!Helpers::isValidPhone($phone)) {
            $errors['phoneError'] = "Phone number must be 10 digits long and contain only numbers.";
        } else {
            $result = ValidationManager::isPhoneTaken($conn, $phone);
            if ($result) {
                $errors['phoneError'] = 'Phone number already exists. Please enter a different phone number.';
            }
        }

        //validate password
        if (empty($password) || empty($confirmPassword)) {
            $errors['passwordError'] = 'Password and Confirm Password are required.';
        } elseif ($password != $confirmPassword) {
            $errors['passwordError'] = "Passwords do not match.";
        } elseif (!Helpers::isValidPassword($password)) {
            $errors['passwordError'] = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character.";
        }

        //validate date of birth
        if (empty($dob)) {
            $errors['dobError'] = "Date of Birth is required.";
        } elseif (!Helpers::isValidDate($dob)) {
            $errors["dobError"] = "Invalid Date format";
        }

        //validate designation
        if (empty($designation)) {
            $errors['designationError'] = "Designation is required.";
        } elseif (!Helpers::isValidDesignation($designation)) {
            $errors['designationError'] = 'Designation can only contain letters, numbers, and spaces.';
        }

        //validate isAdmin
        if (!isset($isAdmin)) {
            $errors['is-adminError'] = 'Is Admin field is required.';
        } elseif ($isAdmin !== '0' && $isAdmin !== '1') {
            $errors['is-adminError'] = 'Is Admin field must be either 0 or 1.';
        }

        return $errors;
    }

    public static function validateEmployeeUpdateData(mysqli $conn, array $data, $emp_id): array
    {
        $errors = [];
        $data = array_map('trim', $data);

        // Optional fields, validate only if present and non-empty

        // Username
        if (!empty($data['username'])) {
            if (!Helpers::isValidUsername($data['username'])) {
                $errors['usernameError'] = "Username can only contain letters, numbers, and underscores.";
            } elseif (ValidationManager::isUsernameTaken($conn, $data['username'])) {
                $errors['usernameError'] = "Username already exists.";
            }
        }

        // First Name
        if (!empty($data['first-name']) && !Helpers::isValidName($data['first-name'])) {
            $errors['firstNameError'] = "First name can only contain letters.";
        }

        // Last Name
        if (!empty($data['last-name']) && !Helpers::isValidName($data['last-name'])) {
            $errors['lastNameError'] = "Last name can only contain letters.";
        }

        // Email
        if (!empty($data['email'])) {
            if (!Helpers::isValidEmail($data['email'])) {
                $errors['emailError'] = "Invalid email format.";
            } elseif (ValidationManager::isEmailTaken($conn, $data['email'])) {
                $errors['emailError'] = "Email already exists.";
            }
        }

        // Password and confirm password
        if (!empty($data["password"]) && !empty($data["confirm-password"]) && ($data["password"] !== $data["confirm-password"])) {
            $errors['passwordError'] = "Passwords do not match.";
        } elseif (!empty($data["password"]) && !Helpers::isValidPassword($data["password"])) {
            $errors['passwordError'] = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character.";
        }

        // Phone
        if (!empty($data['phone'])) {
            if (!Helpers::isValidPhone($data['phone'])) {
                $errors['phoneError'] = "Phone number must be 10 digits long.";
            } elseif (ValidationManager::isPhoneTaken($conn, $data['phone'])) {
                $errors['phoneError'] = "Phone number already exists.";
            }
        }

        // Date of Birth
        if (!empty($data['dob']) && !Helpers::isValidDate($data['dob'])) {
            $errors['dobError'] = "Invalid date format. Use YYYY-MM-DD.";
        }

        // Designation
        if (!empty($data['designation']) && !Helpers::isValidDesignation($data['designation'])) {
            $errors['designationError'] = "Designation can only contain letters, numbers, and spaces.";
        }

        // is_admin
        if (isset($data['is_admin']) && !Helpers::isValidIsAdmin($data['is_admin'])) {
            $errors['is-adminError'] = "Is Admin must be either 0 or 1.";
        }

        return $errors;
    }
}


?>