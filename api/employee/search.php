<?php
require_once BASE_PATH . 'config/database.php';
header('Content-Type: application/json');

$term = $_GET['term'] ?? '';
$term = trim($term);

if (strlen($term) > 50) {
    http_response_code(400);
    echo json_encode(['error' => 'Search term too long.']);
    exit;
}

if (!preg_match('/^[a-zA-Z0-9\s\-_.]+$/', $term)) {
    http_response_code(400);
    echo json_encode(['error' => 'Search term contains invalid characters.']);
    exit;
}

$searchTerm = "%$term%";

$sql = "SELECT emp_id, first_name, last_name 
        FROM employee 
        WHERE (emp_id LIKE ? OR first_name LIKE ? OR last_name LIKE ?) 
        AND is_deleted = 0
        LIMIT 20";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to prepare query.']);
    exit;
}

$stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();

$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        "id" => $row['emp_id'],
        "text" => $row['emp_id'] . ' - ' . $row['first_name'] . ' ' . $row['last_name'],
        "name" => $row['first_name'] . ' ' . $row['last_name'],
    ];
}

echo json_encode(['results' => $data]);
