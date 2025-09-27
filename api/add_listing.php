<?php
require_once __DIR__ . '/db.php';

$input = json_decode(file_get_contents('php://input'), true);
$title = trim($input['title'] ?? '');
$price = floatval($input['price'] ?? 0);

if (!$title || $price <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO listings (title, description, price, city, state, postcode, property_type, bedrooms, bathrooms, area_sq_m, image_url)
VALUES (:title, :desc, :price, :city, :state, :postcode, :ptype, :bedrooms, :bathrooms, :area, :image)");
$params = [
    ':title'=>$title,
    ':desc'=>$input['description'] ?? '',
    ':price'=>$price,
    ':city'=>$input['city'] ?? '',
    ':state'=>$input['state'] ?? '',
    ':postcode'=>$input['postcode'] ?? '',
    ':ptype'=>$input['property_type'] ?? 'House',
    ':bedrooms'=>intval($input['bedrooms'] ?? 0),
    ':bathrooms'=>intval($input['bathrooms'] ?? 0),
    ':area'=>intval($input['area_sq_m'] ?? 0),
    ':image'=>$input['image_url'] ?? ''
];
$stmt->execute($params);

echo json_encode(['success' => true, 'listing_id' => $pdo->lastInsertId()]);