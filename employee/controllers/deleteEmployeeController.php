<?php

require_once BASE_PATH . "utils/Helpers.php";
require_once BASE_PATH . 'employee/models/EmployeeModel.php';
//retrieve employee ID from query
$url = $_SERVER['REQUEST_URI'];
$urlComponents = parse_url($url);
parse_str($urlComponents['query'], $params);

//validate employee ID
$result = Helpers::isNumeric($params['id'] ?? null);

if (!$result) {
    $_SESSION['employeeDeleteError'] = "Invalid employee ID.";
    header("location: " . BASE_URL . "employee/view");
    exit;
}
$emp_id = (int) $params['id'];

try {
    $result = EmployeeModel::deleteEmployee($conn, $emp_id);

    if ($result) {
        $_SESSION['employeeDeleteSuccess'] = "Employee deleted successfully.";
    } else {
        $_SESSION['employeeDeleteError'] = "Error deleting employee. Please try again.";
    }


    header("location: " . BASE_URL . "employee/view");
    exit;

} catch (Exception $e) {
    $_SESSION['employeeDeleteError'] = "Internal Error: Error deleting employee: ";
    header("location: " . BASE_URL . "employee/view");
    exit;
}

?>