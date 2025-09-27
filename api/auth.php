<?php
require_once __DIR__ . '/db.php';

$input = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? '';

if ($action === 'register') {
    $name = trim($input['name'] ?? '');
    $email = trim($input['email'] ?? '');
    $password = $input['password'] ?? '';

    if (!$name || !$email || !$password) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing fields']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'Email already registered']);
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :hash)");
    $stmt->execute([':name'=>$name, ':email'=>$email, ':hash'=>$hash]);

    echo json_encode(['success'=>true]);
    exit;
}

if ($action === 'login') {
    $email = trim($input['email'] ?? '');
    $password = $input['password'] ?? '';

    $stmt = $pdo->prepare("SELECT id, password_hash, role, name FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
        exit;
    }

    $token = bin2hex(random_bytes(24));
    $stmt = $pdo->prepare("INSERT INTO tokens (user_id, token) VALUES (:uid, :token)");
    $stmt->execute([':uid' => $user['id'], ':token' => $token]);

    echo json_encode(['success' => true, 'token' => $token, 'user' => ['id'=>$user['id'],'name'=>$user['name'],'role'=>$user['role']]]);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Invalid action']);
