<?php
class EmployeeModel
{
    public static function create(mysqli $conn, array $data)
    {
        $query = "insert into employee (username,first_name,last_name, email, phone,dob, designation, is_admin, password_hash) values (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        try {

            $stmt = $conn->prepare($query);

            if (!$stmt) {
                return false;
            }
            $stmt->bind_param(
                "sssssssis",
                $data["username"],
                $data["firstName"],
                $data["lastName"],
                $data["email"],
                $data["phone"],
                $data["dob"],
                $data["designation"],
                $data["isAdmin"],
                $data["passwordHash"]
            );

            if ($stmt->execute()) {
                $recordId = $stmt->insert_id;
                $stmt->close();
                return $recordId;
            }
            return false;

        } catch (Exception $e) {
            error_log("EmployeeModel::create error:" . $e->getMessage());
            return false;
        }
    }

    public static function getEmployeeCount(mysqli $conn, string $search = '', string $filter = '')
    {
        try {
            $query = "SELECT COUNT(emp_id) as total FROM employee WHERE is_deleted = 0";
            $params = [];
            $types = "";
            
            // Add search condition
            if (!empty($search)) {
                $query .= " AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone LIKE ? OR username LIKE ? OR designation LIKE ?)";
                $searchTerm = "%$search%";
                $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
                $types .= "ssssss";
            }
            
            // Add filter condition
            if (!empty($filter) && $filter !== 'all') {
                $query .= " AND designation LIKE ?";
                $filterTerm = "%$filter%";
                $params[] = $filterTerm;
                $types .= "s";
            }
            
            if (empty($params)) {
                $result = $conn->query($query);
            } else {
                $stmt = $conn->prepare($query);
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                $result = $stmt->get_result();
            }
            
            if ($result && $row = $result->fetch_assoc()) {
                return (int) $row["total"];
            }
            return 0;
        } catch (Exception $e) {
            error_log("EmployeeModel::getEmployeeCount() Error:" . $e->getMessage());
            return 0;
        }
    }

    public static function getEmployeeById(mysqli $conn, $id)
    {
        try {
            $query = "select username, first_name, last_name, email, phone, dob, designation, is_admin from employee where emp_id = ? AND is_deleted = 0";

            $stmt = $conn->prepare($query);
            if (!$stmt) {
                return null;
            }
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $row = $result->fetch_assoc()) {
                $stmt->close();
                return $row;
            } else {
                $stmt->close();
                return null;
            }
        } catch (Exception $e) {
            error_log("EmployeeModel::getEmployeeById Error:" . $e->getMessage());
            return null;
        }

    }
    public static function getPaginatedEmployeeList(mysqli $conn, int $limit, int $offset, string $search = '', string $filter = '', string $sortBy = 'emp_id', string $sortOrder = 'ASC'): array
    {
        try {
            // Validate sort parameters
            $allowedSortColumns = ['emp_id', 'first_name', 'last_name', 'designation', 'phone', 'email'];
            $allowedSortOrders = ['ASC', 'DESC'];
            
            if (!in_array($sortBy, $allowedSortColumns)) {
                $sortBy = 'emp_id';
            }
            
            if (!in_array(strtoupper($sortOrder), $allowedSortOrders)) {
                $sortOrder = 'ASC';
            }
            
            $query = "SELECT emp_id, first_name, last_name, designation, phone, email FROM employee WHERE is_deleted = 0";
            $params = [];
            $types = "";
            
            // Add search condition
            if (!empty($search)) {
                $query .= " AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone LIKE ? OR username LIKE ? OR designation LIKE ?)";
                $searchTerm = "%$search%";
                $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
                $types .= "ssssss";
            }
            
            // Add filter condition
            if (!empty($filter) && $filter !== 'all') {
                $query .= " AND designation LIKE ?";
                $filterTerm = "%$filter%";
                $params[] = $filterTerm;
                $types .= "s";
            }
            
            // Add sorting and pagination
            $query .= " ORDER BY $sortBy $sortOrder LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            $types .= "ii";
            
            $stmt = $conn->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();

            $employees = [];
            while ($row = $result->fetch_assoc()) {
                $employees[] = $row;
            }
            $stmt->close();

            return $employees;

        } catch (Exception $e) {
            error_log("EmployeeModel::getPaginatedEmployeeList Error:" . $e->getMessage());
            return [];
        }
    }

    public static function updateEmployee(mysqli $conn, int $id, array $data)
    {
        try {
            // Build query dynamically based on provided data
            $updateFields = [];
            $types = "";
            $values = [];
            
            if (isset($data["username"])) {
                $updateFields[] = "username = ?";
                $types .= "s";
                $values[] = $data["username"];
            }
            if (isset($data["firstName"])) {
                $updateFields[] = "first_name = ?";
                $types .= "s";
                $values[] = $data["firstName"];
            }
            if (isset($data["lastName"])) {
                $updateFields[] = "last_name = ?";
                $types .= "s";
                $values[] = $data["lastName"];
            }
            if (isset($data["email"])) {
                $updateFields[] = "email = ?";
                $types .= "s";
                $values[] = $data["email"];
            }
            if (isset($data["phone"])) {
                $updateFields[] = "phone = ?";
                $types .= "s";
                $values[] = $data["phone"];
            }
            if (isset($data["dob"])) {
                $updateFields[] = "dob = ?";
                $types .= "s";
                $values[] = $data["dob"];
            }
            if (isset($data["designation"])) {
                $updateFields[] = "designation = ?";
                $types .= "s";
                $values[] = $data["designation"];
            }
            if (isset($data["isAdmin"])) {
                $updateFields[] = "is_admin = ?";
                $types .= "i";
                $values[] = $data["isAdmin"];
            }
            if (isset($data["passwordHash"])) {
                $updateFields[] = "password_hash = ?";
                $types .= "s";
                $values[] = $data["passwordHash"];
            }
            
            if (empty($updateFields)) {
                error_log("EmployeeModel::updateEmployee Error: No fields to update");
                return false;
            }
            
            $query = "UPDATE employee SET " . implode(", ", $updateFields) . " WHERE emp_id = ?";
            $types .= "i";
            $values[] = $id;
            
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                throw new Exception($conn->error);
            }
            
            $stmt->bind_param($types, ...$values);
            $stmt->execute();
            
            if ($stmt->error) {
                error_log("EmployeeModel::updateEmployee Error:" . $stmt->error);
                $stmt->close();
                return false;
            }
            
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
            
            return $affectedRows > 0;
        } catch (Exception $e) {
            error_log("EmployeeModel::updateEmployee Error:" . $e->getMessage());
            return false;
        }
    }
    public static function deleteEmployee(mysqli $conn, int $id)
    {
        try {
            // Soft delete - mark as deleted instead of actually deleting
            $query = "UPDATE employee SET is_deleted = 1, deleted_at = NOW() WHERE emp_id = ? AND is_deleted = 0";

            $stmt = $conn->prepare($query);
            if (!$stmt) {
                error_log("EmployeeModel::deleteEmployee Error preparing statement: " . $conn->error);
                return false;
            }
            
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            if ($stmt->error) {
                error_log("EmployeeModel::deleteEmployee Error executing: " . $stmt->error);
                $stmt->close();
                return false;
            }
            
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
            
            return $affectedRows > 0;
        } catch (Exception $e) {
            error_log("EmployeeModel::deleteEmployee Error:" . $e->getMessage());
            return false;
        }
    }

    /**
     * Restore a soft-deleted employee
     */
    public static function restoreEmployee(mysqli $conn, int $id): bool
    {
        try {
            $query = "UPDATE employee SET is_deleted = 0, deleted_at = NULL WHERE emp_id = ? AND is_deleted = 1";
            
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                error_log("EmployeeModel::restoreEmployee Error preparing statement: " . $conn->error);
                return false;
            }
            
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            if ($stmt->error) {
                error_log("EmployeeModel::restoreEmployee Error executing: " . $stmt->error);
                $stmt->close();
                return false;
            }
            
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
            
            return $affectedRows > 0;
        } catch (Exception $e) {
            error_log("EmployeeModel::restoreEmployee Error:" . $e->getMessage());
            return false;
        }
    }

    /**
     * Get unique designations for filter dropdown
     */
    public static function getUniqueDesignations(mysqli $conn): array
    {
        try {
            $query = "SELECT DISTINCT designation FROM employee WHERE is_deleted = 0 ORDER BY designation";
            $result = $conn->query($query);
            
            $designations = [];
            while ($row = $result->fetch_assoc()) {
                $designations[] = $row['designation'];
            }
            
            return $designations;
        } catch (Exception $e) {
            error_log("EmployeeModel::getUniqueDesignations Error:" . $e->getMessage());
            return [];
        }
    }

    /**
     * Export employees to CSV format
     */
    public static function exportEmployees(mysqli $conn, string $search = '', string $filter = ''): array
    {
        try {
            // First, check what columns actually exist
            $columnsQuery = "SHOW COLUMNS FROM employee";
            $columnsResult = $conn->query($columnsQuery);
            $availableColumns = [];
            while ($col = $columnsResult->fetch_assoc()) {
                $availableColumns[] = $col['Field'];
            }
            
            // Build SELECT clause with only available columns
            $selectFields = [];
            $desiredFields = ['emp_id', 'username', 'first_name', 'last_name', 'email', 'phone', 'dob', 'designation', 'is_admin'];
            
            foreach ($desiredFields as $field) {
                if (in_array($field, $availableColumns)) {
                    $selectFields[] = $field;
                }
            }
            
            // Add created_at if available, otherwise use a default
            if (in_array('created_at', $availableColumns)) {
                $selectFields[] = 'created_at';
            } else {
                $selectFields[] = "NULL as created_at";
            }
            
            $query = "SELECT " . implode(', ', $selectFields) . " FROM employee WHERE ";
            
            // Check if is_deleted column exists
            if (in_array('is_deleted', $availableColumns)) {
                $query .= "is_deleted = 0";
            } else {
                $query .= "1=1"; // Always true condition if no soft delete
            }
            
            $params = [];
            $types = "";
            
            // Add search condition
            if (!empty($search)) {
                $searchConditions = [];
                $searchFields = ['first_name', 'last_name', 'email', 'phone', 'username', 'designation'];
                
                foreach ($searchFields as $field) {
                    if (in_array($field, $availableColumns)) {
                        $searchConditions[] = "$field LIKE ?";
                        $searchTerm = "%$search%";
                        $params[] = $searchTerm;
                        $types .= "s";
                    }
                }
                
                if (!empty($searchConditions)) {
                    $query .= " AND (" . implode(' OR ', $searchConditions) . ")";
                }
            }
            
            // Add filter condition
            if (!empty($filter) && $filter !== 'all' && in_array('designation', $availableColumns)) {
                $query .= " AND designation LIKE ?";
                $filterTerm = "%$filter%";
                $params[] = $filterTerm;
                $types .= "s";
            }
            
            $query .= " ORDER BY emp_id";
            
            error_log("Export query: " . $query);
            error_log("Export params: " . print_r($params, true));
            
            if (empty($params)) {
                $result = $conn->query($query);
            } else {
                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                $result = $stmt->get_result();
            }
            
            if (!$result) {
                throw new Exception("Query failed: " . $conn->error);
            }
            
            $employees = [];
            while ($row = $result->fetch_assoc()) {
                // Ensure all expected fields exist with defaults
                $employee = [
                    'emp_id' => $row['emp_id'] ?? '',
                    'username' => $row['username'] ?? '',
                    'first_name' => $row['first_name'] ?? '',
                    'last_name' => $row['last_name'] ?? '',
                    'email' => $row['email'] ?? '',
                    'phone' => $row['phone'] ?? '',
                    'dob' => $row['dob'] ?? '',
                    'designation' => $row['designation'] ?? '',
                    'is_admin' => $row['is_admin'] ?? 0,
                    'created_at' => $row['created_at'] ?? date('Y-m-d H:i:s')
                ];
                $employees[] = $employee;
            }
            
            error_log("Export employees count: " . count($employees));
            return $employees;
            
        } catch (Exception $e) {
            error_log("EmployeeModel::exportEmployees Error:" . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return [];
        }
    }
}
?>