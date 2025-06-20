<?php
require_once BASE_PATH . "models/EmployeeModel.php";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $page = isset($_GET["page"]) ? (int) $_GET['page'] : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;

    $paginationError = null;
    $employees = [];
    $totalRecordsCount = 0;
    $totalPages = 1;

    //check for delete success message
    if (isset($_SESSION['employeeDeleteSuccess'])) {
        $deleteSuccess = $_SESSION['employeeDeleteSuccess'];
        unset($_SESSION['employeeDeleteSuccess']);
    }

    //check for delete error message
    if (isset($_SESSION['employeeDeleteError'])) {
        $employeeDeleteError = $_SESSION['employeeDeleteError'];
        unset($_SESSION['employeeDeleteError']);
    }

    try {
        $totalRecordsCount = EmployeeModel::getEmployeeCount($conn);
        $totalPages = max(1, ceil($totalRecordsCount / $limit));

        if ($page < 1 || $page > $totalPages) {
            $paginationError = "Invalid page number. Please try again.";
        } else {
            $employees = EmployeeModel::getPaginatedEmployeeList($conn, $limit, $offset);
            $currentPage = $page;
        }

    } catch (Exception $e) {
        $paginationError = "Error fetching employee data. Please try again.";
        error_log("EmployeeController::fetch error: " . $e->getMessage());
    }

    $pageTitle = "View Employees";
    $viewToInclude = BASE_PATH . "views/employee/view-employees.php";
    require BASE_PATH . "views/layout/layout.php";
    exit;
}
