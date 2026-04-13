<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Metodo nao permitido.']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $phone === '' || $message === '') {
    http_response_code(422);
    echo json_encode(['message' => 'Preencha todos os campos obrigatorios.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['message' => 'Informe um e-mail valido.']);
    exit;
}

try {
    $pdo = new PDO(
        "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    $statement = $pdo->prepare(
        'INSERT INTO leads (name, email, phone, message) VALUES (:name, :email, :phone, :message)'
    );

    $statement->execute([
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'message' => $message,
    ]);

    echo json_encode(['message' => 'Formulario enviado com sucesso.']);
} catch (PDOException $exception) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro ao salvar os dados no banco.']);
}
