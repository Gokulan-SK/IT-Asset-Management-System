<?php

class ValidationManager
{
    public static function validateEmployeeData(mysqli $conn, array $data)
    {
        $errors = [];
        $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).{8,}$/';
        $data = array_map('trim', $data);

        // check if username already exists
        $username = $data['username'];
        $query = "select emp_id from employee where username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors['usernameError'] = "Username already exists. Please enter a different username.";
        }
        $stmt->close();

        //check if email already exists
        $email = $data['email'];
        $query = 'select emp_id from employee where email = ?';
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors['emailError'] = 'Email already exists. Please enter a different email.';
        }
        $stmt->close();

        //check if phone already exists
        $phone = $data['phone'];
        $query = 'select emp_id from employee where phone=?';
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $phone);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors['phoneError'] = 'Phone number already exists. Please enter a different phone number.';
        }
        $stmt->close();

        // Validate username
        if (empty($data['username'])) {
            $errors['usernameError'] = "Username is required.";
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $data['username'])) {
            $errors['usernameError'] = "Username can only contain letters, numbers, and underscores.";
        }



        //validate first name
        if (empty($data['first-name'])) {
            $errors['firstNameError'] = "First name is required.";
        } elseif (!preg_match('/^[a-zA-Z ]+$/', $data['first-name'])) {
            $errors['firstNameError'] = 'First name can only contain letters.';
        }

        //validate last name
        if (empty($data['last-name'])) {
            $errors['lastNameError'] = 'Last name is required.';
        } elseif (!preg_match('/^[a-zA-Z ]+$/', $data['last-name'])) {
            $errors['lastNameError'] = 'Last name can only contain letters.';
        }

        //validate email
        if (empty($data['email'])) {
            $errors['emailError'] = "Email is required.";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['emailError'] = "Invalid email format.";
        }

        //validate phone
        if (empty($data['phone'])) {
            $errors['phoneError'] = 'Phone number is required.';
        } elseif (!preg_match('/^[0-9]{10}$/', $data['phone'])) {
            $errors['phoneError'] = "Phone number must be 10 digits long and contain only numbers.";
        }

        //validate password
        if (empty($data['password']) || empty($data['confirm-password'])) {
            $errors['passwordError'] = 'Password and Confirm Password are required.';
        } elseif ($data['password'] != $data['confirm-password']) {
            $errors['passwordError'] = "Passwords do not match.";
        } elseif (!preg_match($passwordPattern, $data['password'])) {
            $errors['passwordError'] = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character.";
        }

        //validate date of birth
        if (empty($data['dob'])) {
            $errors['dobError'] = "Date of Birth is required.";
        } else {
            try {
                $dob = new DateTime($data['dob']);
                $today = new DateTime();
                $age = $dob->diff($today)->y;
                if ($age < 18 || $age > 80) {
                    $errors['dobError'] = "Age must be between 18 and 80 years.";
                }
            } catch (Exception $e) {
                $errors['dobError'] = "Invalid date format for Date of Birth.";
            }
        }

        //validate designation
        if (empty($data["designation"])) {
            $errors['designationError'] = "Designation is required.";
        } elseif (!preg_match('/^[a-zA-Z0-9 ]+$/', $data['designation'])) {
            $errors['designationError'] = 'Designation can only contain letters, numbers, and spaces.';
        }

        //validate isAdmin
        if (!isset($data['is-admin'])) {
            $errors['is-adminError'] = 'Is Admin field is required.';
        } elseif ($data['is-admin'] !== '0' && $data['is-admin'] !== '1') {
            $errors['is-adminError'] = 'Is Admin field must be either 0 or 1.';
        }

        return $errors;
    }

    public static function isNumericId($value)
    {
        return preg_match("/^[0-9]+$/", $value);
    }
}


?>