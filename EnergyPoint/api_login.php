<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';
require 'firebase_auth.php'; // Este arquivo deve conter a função verificar_token_firebase

// Inicia sessão (se ainda não iniciada) - Mover para o topo, antes de qualquer output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$idToken = $data['idToken'] ?? '';

if (!$idToken) {
    http_response_code(400);
    echo json_encode(['status' => 'erro', 'mensagem' => 'ID Token não fornecido']);
    exit;
}

// Valida o token do Firebase
$dadosToken = verificar_token_firebase($idToken); // Renomeei para evitar conflito com $dados['name']

if ($dadosToken) {
    $email = $dadosToken['email'] ?? '';
    // Pegar o nome de exibição do token (claims do Firebase ID Token)
    // Para logins com Google, 'name' é comum. Para e-mail/senha, é o displayName que setamos.
    $nome_do_token = $dadosToken['name'] ?? $dadosToken['displayName'] ?? $email; 

    if (!$email) {
        http_response_code(400);
        echo json_encode(['status' => 'erro', 'mensagem' => 'E-mail não encontrado no token']);
        exit;
    }

    // Busca usuário pelo e-mail no seu banco de dados
    $usuario = buscar_usuario_por_email($email);

    // Se não existir, cadastra
    if (!$usuario) {
        $uid = $dadosToken['uid'] ?? $dadosToken['user_id'] ?? $dadosToken['sub'] ?? null;
        cadastrar_usuario_firebase($uid, $nome_do_token, $email); // Use o nome do token ao cadastrar
        $usuario = buscar_usuario_por_email($email); // Pega o usuário recém-criado
    } else {
        // Se o usuário existir, atualize o nome dele no seu BD caso o nome do token seja mais recente/válido.
        // Isso é útil se o displayName for atualizado no Firebase após o cadastro inicial.
        if ($usuario['nome'] !== $nome_do_token) {
            atualizar_nome_usuario_por_email($email, $nome_do_token);
            $usuario['nome'] = $nome_do_token; // Atualiza o array $usuario para a sessão
        }
    }

    // Armazena informações na sessão PHP
    $_SESSION['usuario_id'] = $usuario['id'];
    
    // Salva o PRIMEIRO NOME do usuário na sessão
    // Garanta que $usuario['nome'] esteja preenchido
    $nome_completo_para_sessao = $usuario['nome'] ?? $email; // Fallback caso 'nome' esteja vazio
    $primeiro_nome = explode(' ', trim($nome_completo_para_sessao))[0];
    $_SESSION['usuario_nome'] = $primeiro_nome; // Agora este é o primeiro nome

    echo json_encode([
        'status' => 'ok',
        'usuario_id' => $usuario['id'],
        'usuario_nome' => $primeiro_nome // Retorna o primeiro nome também para o frontend se precisar
    ]);
} else {
    http_response_code(401);
    echo json_encode(['status' => 'erro', 'mensagem' => 'Token inválido ou expirado']);
}
?>