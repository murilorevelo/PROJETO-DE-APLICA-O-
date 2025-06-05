<?php 
include 'db.php';

// Verificar se o usuário está logado
$esta_logado = isset($_SESSION['usuario_id']);

// Buscar pontos do banco de dados
$pontos = listar_pontos();

// Incluir o cabeçalho
include 'header.php'; 
?>

<main class="secao">
    <div class="container">
        <h2>Pontos de Carregamento</h2>
        
        <!-- Barra de busca simplificada -->
        <div class="busca-simples">
            <div class="campo-busca">
                <input type="text" id="filtro" placeholder="Buscar por nome...">
                <i class="fas fa-search"></i>
            </div>
            
            <?php if ($esta_logado): ?>
                <a href="adicionar_ponto.php" class="botao botao-verde">
                    <i class="fas fa-plus"></i> Adicionar Ponto
                </a>
            <?php else: ?>
                <a href="login.php" class="botao botao-azul">
                    <i class="fas fa-sign-in-alt"></i> Faça login para adicionar
                </a>
            <?php endif; ?>
        </div>
        
        <!-- Botão de calcular rota -->
        <a href="mapa.php" class="botao botao-verde botao-grande botao-central">
            <i class="fas fa-route"></i> Encontrar rota com carregadores
        </a>
        
        <?php if (empty($pontos)): ?>
            <div class="cartao" style="text-align: center; padding: 40px;">
                <i class="fas fa-map-marker-alt" style="font-size: 48px; color: #888; margin-bottom: 20px;"></i>
                <p>Nenhum ponto de carregamento cadastrado.</p>
                <?php if ($esta_logado): ?>
                    <a href="adicionar_ponto.php" class="botao botao-verde">Adicionar seu primeiro ponto</a>
                <?php else: ?>
                    <a href="login.php" class="botao botao-verde">Faça login para adicionar pontos</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="grid" id="pontos-grid">
                <?php foreach ($pontos as $ponto): ?>
                    <div class="cartao" data-nome="<?php echo $ponto['nome']; ?>">
                        <h3><?php echo $ponto['nome']; ?></h3>
                        <p><i class="fas fa-map-pin"></i> <strong>CEP:</strong> <?php echo $ponto['cep']; ?></p>
                        <p><i class="fas fa-map-location-dot"></i> <strong>Número:</strong> <?php echo $ponto['numero'] ?? 'Não informado'; ?>
                        </p>
                        <p><i class="fas fa-map-marker-alt"></i> <strong>Complemento:</strong> <?php echo $ponto['complemento'] ?? 'Não informado'; ?></p>
                        <p><i class="fas fa-clock"></i> <strong>Horário:</strong> <?php echo $ponto['horario_funcionamento']; ?></p>
                        
                        <p style="font-size: 14px; color: #888;">
                            <i class="fas fa-calendar-alt"></i> Adicionado em: <?php echo date('d/m/Y', strtotime($ponto['data_cadastro'])); ?>
                        </p>
                        
                        <?php if ($esta_logado && $ponto['usuario_id'] == $_SESSION['usuario_id']): ?>
                            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 15px;">
                                <a href="editar_ponto.php?id=<?php echo $ponto['id']; ?>" class="botao botao-azul botao-pequeno">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="excluir_ponto.php?id=<?php echo $ponto['id']; ?>" class="botao botao-vermelho botao-pequeno" onclick="return confirm('Tem certeza que deseja excluir este ponto?');">
                                    <i class="fas fa-trash-alt"></i> Excluir
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
    // Script simplificado para filtrar pontos
    document.addEventListener('DOMContentLoaded', function() {
        const filtro = document.getElementById('filtro');
        const cards = document.querySelectorAll('#pontos-grid .cartao');
        
        filtro.addEventListener('input', function() {
            const texto = this.value.toLowerCase();
            
            cards.forEach(card => {
                const nome = card.getAttribute('data-nome').toLowerCase();
                card.style.display = nome.includes(texto) ? 'block' : 'none';
            });
        });
    });
    </script>
</main>

<?php include 'footer.php'; ?>