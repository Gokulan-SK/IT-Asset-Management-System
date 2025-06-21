<?php
require_once BASE_PATH . "employee/models/EmployeeModel.php";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $page = isset($_GET["page"]) ? (int) $_GET['page'] : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;

    $paginationError = null;
    $employees = [];
    $totalRecordsCount = 0;
    $totalPages = 1;
    $employeeDeleteError = null;
    $employeeDeleteSuccess = null;

    //check for delete success message
    if (isset($_SESSION['employeeDeleteSuccess'])) {
        $employeeDeleteSuccess = $_SESSION['employeeDeleteSuccess'];
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

        if ($totalRecordsCount == 0) {
            $currentPage = 1;
            $totalPages = 1;
        } else {
            $totalPages = max(1, ceil($totalRecordsCount / $limit));
            if ($page < 1) {
                $currentPage = 1;
            } elseif ($page > $totalPages) {
                $currentPage = $totalPages;
            } else {
                $currentPage = $page;
            }
        }

        if ($page < 1 || $page > $totalPages) {
            $paginationError = "Invalid page number. Please try again.";
        } else {
            $employees = EmployeeModel::getPaginatedEmployeeList($conn, $limit, $offset);
            $currentPage = $page;
        }

    } catch (Exception $e) {
        $paginationError = "Error fetching employee data. Please try again.";
    }

    $pageTitle = "View Employees";
    $viewToInclude = BASE_PATH . "employee/views/view-employees.php";
    require BASE_PATH . "views/layouts/layout.php";
    exit;
}
