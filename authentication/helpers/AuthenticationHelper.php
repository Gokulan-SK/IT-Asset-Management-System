<?php
// authentication/helpers/AuthenticationHelper.php
require_once BASE_PATH . "authentication/models/AuthenticationModel.php";

class AuthenticationHelper
{
    public static function isLoggedIn(mysqli $conn)
    {
        try {

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
                session_regenerate_id();
            }

            if (isset($_SESSION["user"]) && is_array($_SESSION["user"])) {
                return true;
            } else {
                if (isset($_COOKIE["remember_me"])) {
                    $user = AuthenticationModel::getUserBySessionCookie($conn, $_COOKIE["remember_me"]);
                    if ($user) {
                        $_SESSION["user"] = [
                            "id" => $user["emp_id"],
                            "username" => $user["username"],
                            "firstName" => $user["first_name"],
                            "email" => $user["email"],
                            "isAdmin" => $user["is_admin"]
                        ];
                        return true;
                    }
                } else {
                    return false;
                }
            }
        } catch (Exception $e) {
            error_log("AuthenticationHelper::isLoggedIn() Error: " . $e->getMessage());
            return false;
        }
    }
}

?>