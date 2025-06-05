<?php
include 'db.php';
header('Content-Type: application/json');

// Receber dados JSON
$dados = json_decode(file_get_contents('php://input'), true);
$uid = $dados['uid'] ?? '';
$nome = $dados['nome'] ?? '';
$email = $dados['email'] ?? '';

if (!$uid || !$nome || !$email) {
    echo json_encode(['erro' => 'Dados incompletos.']);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO usuarios (uid, nome, email) VALUES (?, ?, ?)");
$sucesso = $stmt->execute([$uid, $nome, $email]);

if ($sucesso) {
    echo json_encode(['sucesso' => true]);
} else {
    echo json_encode(['erro' => 'Erro ao salvar no banco.']);
}
?>