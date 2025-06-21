<?php
ob_start();

require_once BASE_PATH . 'utils/validators/EmployeeValidator.php';
require_once BASE_PATH . "employee/models/EmployeeModel.php";

$pageTitle = "Update Employee";
$viewToInclude = BASE_PATH . "employee/views/EmployeeForm.php";
$action = "employee/update";

// GET request - Load form with existing data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $url = $_SERVER['REQUEST_URI'];
    $urlComponents = parse_url($url);
    parse_str($urlComponents['query'], $params);

    $emp_id = isset($params['id']) ? (int) $params['id'] : null;

    if (!$emp_id || !is_numeric($emp_id)) {
        $errorMessage = "Invalid employee ID.";
        require BASE_PATH . "views/layouts/layout.php";
        exit;
    }

    $employeeData = EmployeeModel::getEmployeeById($conn, $emp_id);
    if (!$employeeData) {
        $errorMessage = "Employee not found.";
        require BASE_PATH . "views/layouts/layout.php";
        exit;
    }

    $formData = [
        'id' => $emp_id,
        'username' => $employeeData['username'],
        'first-name' => $employeeData['first_name'],
        'last-name' => $employeeData['last_name'],
        'email' => $employeeData['email'],
        'phone' => $employeeData['phone'],
        'dob' => $employeeData['dob'],
        'designation' => $employeeData['designation'],
        'is-admin' => $employeeData['is_admin']
    ];

    require BASE_PATH . "views/layouts/layout.php";
    exit;
}

// POST request - Validate and Update
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = $_POST;
    $emp_id = (int) ($formData['id'] ?? 0);
    $action = "employee/update?id=" . $emp_id;

    // Validate update data
    $errors = EmployeeValidator::validateForUpdate($conn, $formData, $emp_id);

    if (!empty($errors)) {
        require BASE_PATH . "views/layouts/layout.php";
        exit;
    }

    $updateData = [
        "username" => $formData["username"],
        "firstName" => $formData["first-name"],
        "lastName" => $formData["last-name"],
        "email" => $formData["email"],
        "phone" => $formData["phone"],
        "dob" => $formData["dob"],
        "designation" => $formData["designation"],
        "isAdmin" => (int) $formData["is-admin"]
    ];

    if (!empty($formData['password'])) {
        $updateData['passwordHash'] = password_hash($formData['password'], PASSWORD_DEFAULT);
    }

    $result = EmployeeModel::updateEmployee($conn, $emp_id, $updateData);

    if ($result) {
        $_SESSION['success'] = "Employee updated successfully.";
        header("Location: " . BASE_URL . "employee/add");
        exit;
    } else {
        $errorMessage = "Error updating employee.";
        require BASE_PATH . "views/layouts/layout.php";
        exit;
    }
}
