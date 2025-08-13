<?php


class AuthenticationModel
{
    public static function getUser(mysqli $conn, string $username)
    {
        $query = "select emp_id,username, first_name, email, password_hash, is_admin from employee where (email = ? or username = ? or phone = ?) AND is_deleted = 0";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $username, $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else {
            return false;
        }
    }
    public static function storeSessionCookie(mysqli $conn, int $emp_id, string $token)
    {
        $query = "update employee set session_cookie = ? where emp_id = ?";

        try {

            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $token, $emp_id);
            if (!$stmt->execute()) {
                error_log("AuthenticationModel::storeSessionCookie() Error: " . $stmt->error);
                $stmt->close();
                return false;
            }
            $result = $stmt->affected_rows > 0;
            $stmt->close();
            return $result;
        } catch (Exception $e) {
            error_log("AuthenticationModel::storeSessionCookie() Error: " . $e->getMessage());
            return false;
        }
    }

    public static function getUserBySessionCookie(mysqli $conn, string $sessionCookie)
    {
        $query = "select emp_id, username, first_name, email, is_admin from employee where session_cookie = ? AND is_deleted = 0";

        try {

            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $sessionCookie);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row;
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log("AuthenticationModel::getUserBySessionCookie() Error:" . $e->getMessage());
        }
    }
}

?>