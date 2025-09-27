<?php
require_once __DIR__ . '/db.php';

$q = trim($_GET['q'] ?? '');
$city = trim($_GET['city'] ?? '');
$min_price = $_GET['min_price'] ?? null;
$max_price = $_GET['max_price'] ?? null;
$bedrooms = $_GET['bedrooms'] ?? null;
$ptype = $_GET['property_type'] ?? '';

$where = [];
$params = [];

if ($q !== '') {
    $where[] = "(title LIKE :q OR description LIKE :q)";
    $params[':q'] = "%$q%";
}
if ($city !== '') {
    $where[] = "city = :city";
    $params[':city'] = $city;
}
if (is_numeric($min_price)) {
    $where[] = "price >= :min_price";
    $params[':min_price'] = $min_price;
}
if (is_numeric($max_price)) {
    $where[] = "price <= :max_price";
    $params[':max_price'] = $max_price;
}
if (is_numeric($bedrooms)) {
    $where[] = "bedrooms = :bedrooms";
    $params[':bedrooms'] = intval($bedrooms);
}
if ($ptype !== '') {
    $where[] = "property_type = :ptype";
    $params[':ptype'] = $ptype;
}

$sql = "SELECT * FROM listings";
if (count($where) > 0) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY price ASC LIMIT 200";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll();

echo json_encode(['count' => count($results), 'data' => $results]);
