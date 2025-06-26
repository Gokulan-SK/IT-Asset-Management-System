<?php

require_once BASE_PATH . "utils/ValidationHelper.php";
require_once BASE_PATH . 'employee/models/EmployeeModel.php';
//retrieve employee ID from query


//validate employee ID
$result = ValidationHelper::isNumeric($_POST['id'] ?? null);

if (!$result) {
    $_SESSION['error'] = "Invalid employee ID.";
    header("location: " . BASE_URL . "employee/view");
    exit;
}
$emp_id = (int) $_POST['id'];

try {
    $result = EmployeeModel::deleteEmployee($conn, $emp_id);

    if ($result) {
        $_SESSION['success'] = "Employee deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting employee. Please try again.";
    }


    header("location: " . BASE_URL . "employee/view");
    exit;

} catch (Exception $e) {
    $_SESSION['error'] = "Internal Error: Error deleting employee: ";
    header("location: " . BASE_URL . "employee/view");
    exit;
}

?>