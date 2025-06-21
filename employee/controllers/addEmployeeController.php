<?php
require_once BASE_PATH . 'utils/validators/EmployeeValidator.php';
require_once BASE_PATH . 'employee/models/EmployeeModel.php';

$viewToInclude = BASE_PATH . "employee/views/EmployeeForm.php";
$pageTitle = "Add Employee";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = $_POST;
    $action = "employee/add";

    // Validate input
    $errors = EmployeeValidator::validateForCreate($conn, $formData);

    if (!empty($errors)) {
        require BASE_PATH . "views/layouts/layout.php";
        exit;
    }

    $data = [
        "username" => $formData["username"],
        "firstName" => $formData["first-name"],
        "lastName" => $formData["last-name"],
        "email" => $formData["email"],
        "phone" => $formData["phone"],
        "dob" => $formData["dob"],
        "designation" => $formData["designation"],
        "isAdmin" => (int) $formData["is-admin"],
        "passwordHash" => password_hash($formData["password"], PASSWORD_DEFAULT)
    ];

    $result = EmployeeModel::create($conn, $data);

    if ($result) {
        $_SESSION['success'] = "Employee added successfully.";
        $formData = null;
        header("Location: " . BASE_URL . "employee/add");
        exit;
    } else {
        $errorMessage = "Error adding employee.";
        require BASE_PATH . "views/layouts/layout.php";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $success = $_SESSION['success'] ?? '';
    unset($_SESSION['success']);
    require BASE_PATH . "views/layouts/layout.php";
    exit;
}
