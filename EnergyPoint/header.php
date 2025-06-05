<?php
// Garante que a sessão esteja ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pontos de Carregamento para Carros Elétricos</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <header class="cabecalho">
        <div class="container">
            <a href="index.php" class="logo">
                <i class="fas fa-bolt"></i> EnergyPoint
            </a>
            <nav class="menu">
                <a href="pontos.php">Pontos</a>
                <a href="politicas.php">Políticas</a>
                
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <span class="nome-usuario">Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário'); ?></span>
                    <a href="logout.php" class="botao botao-vermelho botao-pequeno">Sair</a>
                <?php else: ?>
                    <a href="login.php" class="botao botao-azul botao-pequeno">Entrar</a>
                    <a href="cadastro.php" class="botao botao-verde botao-pequeno">Cadastrar</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>