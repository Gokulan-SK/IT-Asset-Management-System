<?php
require_once BASE_PATH . 'utils/validationManager.php';
require_once BASE_PATH . 'models/EmployeeModel.php';

$viewToInclude = BASE_PATH . "views/employee/add-employee.php";
$pageTitle = "Add Employee";
$pageStyles = [BASE_URL . "public/css/form.css"];

//Create Employee
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = ValidationManager::validateEmployeeData($conn, $_POST);
    $formData = $_POST;

    if (!empty($errors)) {
        require BASE_PATH . "views/layout/layout.php";
        exit;
    }

    $data = [
        "username" => $_POST["username"],
        "firstName" => $_POST["first-name"],
        "lastName" => $_POST["last-name"],
        "email" => $_POST["email"],
        "phone" => $_POST["phone"],
        "dob" => $_POST["dob"],
        "designation" => $_POST["designation"],
        "isAdmin" => (int) $_POST["is-admin"],
        "passwordHash" => password_hash($_POST["password"], PASSWORD_DEFAULT)
    ];

    $result = EmployeeModel::create($conn, $data);

    if ($result) {
        $_SESSION['success'] = "Employee added successfully.";
        $formData = null;
        header("Location: " . BASE_URL . "employee/add");
        exit;

    } else {
        $errorMessage = "Error adding employee.";
    }

    require BASE_PATH . "views/layout/layout.php";
    exit;
}
// Handle GET request or after POST render
else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $success = $_SESSION['success'] ?? '';
    unset($_SESSION['success']);
    require BASE_PATH . "views/layout/layout.php";
    exit;
}
