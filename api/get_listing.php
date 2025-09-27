<?php
require_once __DIR__ . '/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'No listing ID provided']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM listings WHERE id = :id");
$stmt->execute([':id' => $id]);
$listing = $stmt->fetch();

if (!$listing) {
    http_response_code(404);
    echo json_encode(['error' => 'Listing not found']);
    exit;
}

echo json_encode($listing);