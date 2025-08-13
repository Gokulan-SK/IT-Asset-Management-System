<?php
require_once BASE_PATH . "employee/models/EmployeeModel.php";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $page = isset($_GET["page"]) ? (int) $_GET['page'] : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;
    
    // Get search, filter, and sort parameters
    $search = isset($_GET["search"]) ? trim($_GET["search"]) : '';
    $filter = isset($_GET["filter"]) ? trim($_GET["filter"]) : '';
    $sort = isset($_GET["sort"]) ? trim($_GET["sort"]) : 'emp_id';
    $order = isset($_GET["order"]) && in_array($_GET["order"], ['ASC', 'DESC']) ? $_GET["order"] : 'ASC';
    $export = isset($_GET["export"]) ? $_GET["export"] : false;

    $paginationError = null;
    $employees = [];
    $totalRecordsCount = 0;
    $totalPages = 1;
    $errorMessage = null;
    $successMessage = null;
    $designations = [];

    //check for success message
    if (isset($_SESSION['success'])) {
        $successMessage = $_SESSION['success'];
        unset($_SESSION['success']);
    }

    //check for error message
    if (isset($_SESSION['error'])) {
        $errorMessage = $_SESSION['error'];
        unset($_SESSION['error']);
    }

    // Handle export request
    if ($export === 'csv') {
        try {
            error_log("Export CSV requested - Search: '$search', Filter: '$filter'");
            $exportData = EmployeeModel::exportEmployees($conn, $search, $filter);
            error_log("Export data count: " . count($exportData));
            
            if (empty($exportData)) {
                error_log("No export data found, redirecting with error");
                $_SESSION['error'] = "No employee data found to export.";
                header("Location: " . BASE_URL . "employee/view");
                exit;
            }
            
            // Set headers for CSV download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="employees_' . date('Y-m-d_H-i-s') . '.csv"');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            
            // Output CSV headers
            $csvHeaders = ['Employee ID', 'Username', 'First Name', 'Last Name', 'Email', 'Phone', 'Date of Birth', 'Designation', 'Admin Status', 'Created Date'];
            echo implode(',', $csvHeaders) . "\n";
            
            // Output CSV data
            foreach ($exportData as $row) {
                $csvRow = [
                    $row['emp_id'],
                    '"' . str_replace('"', '""', $row['username']) . '"',
                    '"' . str_replace('"', '""', $row['first_name']) . '"',
                    '"' . str_replace('"', '""', $row['last_name']) . '"',
                    '"' . str_replace('"', '""', $row['email']) . '"',
                    '"' . str_replace('"', '""', $row['phone']) . '"',
                    $row['dob'],
                    '"' . str_replace('"', '""', $row['designation']) . '"',
                    $row['is_admin'] ? 'Yes' : 'No',
                    date('Y-m-d H:i:s', strtotime($row['created_at']))
                ];
                echo implode(',', $csvRow) . "\n";
            }
            exit;
        } catch (Exception $e) {
            error_log("Export error: " . $e->getMessage());
            $_SESSION['error'] = "Error exporting employee data: " . $e->getMessage();
            header("Location: " . BASE_URL . "employee/view");
            exit;
        }
    }

    try {
        // Get unique designations for filter dropdown
        $designations = EmployeeModel::getUniqueDesignations($conn);
        
        $totalRecordsCount = EmployeeModel::getEmployeeCount($conn, $search, $filter);
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
            $employees = EmployeeModel::getPaginatedEmployeeList($conn, $limit, $offset, $search, $filter, $sort, $order);
            $currentPage = $page;
        }

    } catch (Exception $e) {
        $paginationError = "Error fetching employee data. Please try again.";
    }

    $pageTitle = "View Employees";
    $viewToInclude = BASE_PATH . "employee/views/view-employees.php";
    $pageStyles = [
        BASE_URL . "public/css/pages/employee-list.css?v=" . (time() + 1),
    ];
    $pageScripts = [
        BASE_URL . "public/js/employee/employee-list.js?v=" . (time() + 2),
    ];
    require BASE_PATH . "views/layouts/layout.php";
    exit;
}
