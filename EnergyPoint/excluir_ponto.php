<?php
include 'db.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Verificar se o ID foi fornecido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: pontos.php');
    exit;
}

$id = $_GET['id'];

// Buscar o ponto para verificar se pertence ao usuário
$ponto = obter_ponto($id);

// Verificar se o ponto existe e pertence ao usuário
if (!$ponto || $ponto['usuario_id'] != $_SESSION['usuario_id']) {
    header('Location: pontos.php');
    exit;
}

// Excluir o ponto
$sucesso = excluir_ponto($id);

// Redirecionar para a página de pontos
header('Location: pontos.php');
exit;
?>