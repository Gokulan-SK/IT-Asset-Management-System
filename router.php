<?php

require_once BASE_PATH . "authentication/helpers/AuthenticationHelper.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
    session_regenerate_id();
}

// Get route from URL, remove query string
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = trim(str_replace(BASE_URL, '', $request), '/');

// Public routes that should work without login
$publicRoutes = ['login', 'forgot-password', 'logout'];

// Include route maps
$dashboardRoutes = require 'dashboard-module/routes/routes.php';
$assetRoutes = require 'asset/routes/routes.php';
$employeeRoutes = require 'employee/routes/routes.php';
$authenticationRoutes = require 'authentication/routes/routes.php';
$assetLedgerRoutes = require 'asset-ledger/routes/routes.php';
$apiRoutes = require 'api/routes.php';

// Combine all protected/admin routes
$adminRoutes = array_merge($dashboardRoutes, $assetRoutes, $employeeRoutes, $assetLedgerRoutes, $apiRoutes);

// Check if user is logged in
$isLoggedIn = AuthenticationHelper::isLoggedIn($conn);

if (in_array($route, $publicRoutes) && isset($authenticationRoutes[$route])) {
    require $authenticationRoutes[$route];
    exit;
}

// Block access to protected routes if not logged in
if (!$isLoggedIn) {
    header("Location: " . BASE_URL . "login");
    exit;
}

// Allow only admins to access admin routes
if (array_key_exists($route, $adminRoutes) && ($_SESSION["user"]["isAdmin"] ?? false)) {
    require $adminRoutes[$route];
    exit;
}

// Everything else
http_response_code(404);
echo "<h1>404 - Page Not Found</h1>";
