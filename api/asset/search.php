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

$sql = "SELECT asset_id, name 
        FROM asset 
        WHERE (name LIKE ? OR asset_id LIKE ?) 
        AND (asset_status = 'in_storage'  OR asset_status = 'inactive')
        LIMIT 20";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to prepare query.']);
    exit;
}

$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();

$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        "id" => $row['asset_id'],
        "text" => $row['asset_id'] . ' - ' . $row['name']
    ];
}

echo json_encode(['results' => $data]);
