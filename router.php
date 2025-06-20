<?php

// Get route from URL, remove query string
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove leading slash and base folder if using localhost/project/
$route = str_replace(BASE_URL, '', $request);

// Route table — define your routes here
$routes = [
    '' => 'controllers/dashboardController.php',
    'dashboard' => 'controllers/dashboardController.php',
    'employee/add' => 'controllers/employee/addEmployeeController.php',
    'employee/view' => 'controllers/employee/viewEmployeeController.php',
    'employee/update' => 'controllers/employee/updateEmployeeController.php',
    'employee/delete' => 'controllers/employee/deleteEmployeeController.php',
    'asset/add' => 'controllers/asset/addAssetController.php',
    'asset/view' => 'controllers/asset/viewAssetController.php',
    'assetDelete' => 'controllers/asset/deleteAssetController.php',
    'asset/allocate' => 'controllers/asset/allocateAssetController.php',
];

// Load corresponding controller or show 404
if (array_key_exists($route, $routes)) {
    require $routes[$route];
} else {
    http_response_code(404);
    echo "<h1>404 - Page Not Found</h1>";
}
