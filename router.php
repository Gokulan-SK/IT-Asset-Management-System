<?php
// Get route from URL, remove query string
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Normalize route path cleanly
$route = trim(str_replace(BASE_URL, '', $request), '/');

// Include routes from individual modules
$dashboardRoutes = require 'dashboard-module/routes/routes.php';
$assetRoutes = require 'asset/routes/routes.php';
$employeeRoutes = require 'employee/routes/routes.php';

// Merge the routes into a single array
$routes = array_merge($dashboardRoutes, $assetRoutes, $employeeRoutes);

// Load corresponding controller or show 404
if (array_key_exists($route, $routes)) {
    require $routes[$route];
} else {
    http_response_code(404);
    echo "<h1>404 - Page Not Found</h1>";
}
?>