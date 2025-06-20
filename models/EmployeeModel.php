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

    public static function getEmployeeCount(mysqli $conn)
    {
        try {
            $query = "select count(emp_id) as total from employee";
            $result = $conn->query($query);
            if ($result && $row = $result->fetch_assoc()) {
                return (int) $row["total"];
            }
            return 0;
        } catch (Exception $e) {
            error_log("EmployeeModel::getEmployeeCount() Error:" . $e->getMessage());
            return 0;
        }
    }

    public static function getPaginatedEmployeeList(mysqli $conn, int $limit, int $offset): array
    {
        try {
            $query = " select emp_id, concat(first_name,' ',last_name) as name, designation, phone, email from employee order by emp_id limit ? offset ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $limit, $offset);
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

    public static function deleteEmployee(mysqli $conn, int $id)
    {
        try {
            $query = "delete from employee where emp_id = ?";

            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                $stmt->close();
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log("EmployeeModel::deleteEmployee Error:" . $e->getMessage());
            return false;
        }
    }
}
?>