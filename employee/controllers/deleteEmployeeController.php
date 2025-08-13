<?php

require_once BASE_PATH . "utils/ValidationHelper.php";
require_once BASE_PATH . 'employee/models/EmployeeModel.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Invalid request method.";
    header("location: " . BASE_URL . "employee/view");
    exit;
}

// Validate employee ID
$result = ValidationHelper::isNumeric($_POST['id'] ?? null);

if (!$result) {
    $_SESSION['error'] = "Invalid employee ID.";
    header("location: " . BASE_URL . "employee/view");
    exit;
}

$emp_id = (int) $_POST['id'];

// Safety check - prevent deleting current user
if (isset($_SESSION["user"]["id"]) && $_SESSION["user"]["id"] == $emp_id) {
    $_SESSION['error'] = "You cannot delete your own account.";
    header("location: " . BASE_URL . "employee/view");
    exit;
}

try {
    $result = EmployeeModel::deleteEmployee($conn, $emp_id);

    if ($result) {
        $_SESSION['success'] = "Employee deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting employee. Employee may not exist or is already deleted.";
    }

    header("location: " . BASE_URL . "employee/view");
    exit;

} catch (Exception $e) {
    error_log("Delete Employee Error: " . $e->getMessage());
    $_SESSION['error'] = "Internal error occurred while deleting employee.";
    header("location: " . BASE_URL . "employee/view");
    exit;
}

?>