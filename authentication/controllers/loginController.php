<?php

// authentication/controllers/loginController.php

require_once BASE_PATH . "authentication/models/AuthenticationModel.php";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    require_once BASE_PATH . "authentication/views/login.php";
    exit;
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = htmlspecialchars(trim($_POST["username"] ?? ''), ENT_QUOTES, 'UTF-8');
    $password = trim($_POST["password"]);

    $user = AuthenticationModel::getUser($conn, $username);

    $errors = [];

    if (!$user) {
        $errors["usernameError"] = "Invalid username.";
        require_once BASE_PATH . "authentication/views/login.php";
        exit;
    }
    if (!password_verify($password, $user["password_hash"])) {
        $errors["passwordError"] = "Invalid password.";
        require_once BASE_PATH . "authentication/views/login.php";
        exit;
    }

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $token = bin2hex(random_bytes(32));

    setcookie("remember_me", $token, time() + 60 * 60 * 24 * 30, "/");

    AuthenticationModel::storeSessionCookie($conn, $user["emp_id"], $token);

    $_SESSION["user"] = [
        "id" => $user["emp_id"],
        "username" => $user["username"],
        "firstName" => $user["first_name"],
        "email" => $user["email"],
        "isAdmin" => $user["is_admin"]
    ];

    header("Location: " . BASE_URL . "dashboard");
    exit;
}
?>