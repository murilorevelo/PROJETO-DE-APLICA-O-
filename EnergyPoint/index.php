<?php 
include 'db.php';
include 'header.php'; 
?>

<main>
    <!-- Banner Principal -->
    <section class="secao secao-cinza">
        <div class="container">
            <div class="banner">
                <div class="banner-texto">
                    <h1>Encontre pontos de carregamento para seu carro elétrico</h1>
                    <p>Descubra, adicione estações de carregamento para veículos elétricos em todo o Brasil. Contribua com a mobilidade sustentável e conecte-se ao futuro!</p>
                    <div>
                        <a href="pontos.php" class="botao botao-verde">
                             <i class="fas fa-map-marker-alt"></i> Ver Pontos de Recarga
                        </a>
                        <?php if (isset($_SESSION['usuario_id'])): ?>
                            <a href="adicionar_ponto.php" class="botao botao-azul">
                                <i class="fas fa-plus-circle"></i> Adicionar Novo Ponto
                            </a>
                        <?php else: ?>
                            <a href="cadastro.php" class="botao botao-azul">
                                <i class="fas fa-user-plus"></i> Criar Conta
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="banner-imagem">
                    <img src="https://cdn.oantagonista.com/uploads/2025/02/Quanto-custa-para-carregar-um-carro-eletrico.jpg" alt="Carro elétrico carregando">
                </div>
            </div>
        </div>
    </section>

    <!-- Seção de Vantagens com 3 Cards -->
    <section class="secao">
        <div class="container">
            <h2>Por que usar nossa plataforma?</h2>
            
            <div class="grid-3">
                <div class="cartao">
                    <div class="icone-destaque">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>Encontre Facilmente</h3>
                    <p>Localize pontos de carregamento próximos a você ou em seu destino de viagem com nossa interface intuitiva.</p>
                </div>
                
                <div class="cartao">
                    <div class="icone-destaque">
                        <i class="fas fa-plus"></i>
                    </div>
                    <h3>Contribua</h3>
                    <p>Adicione novos pontos de carregamento e ajude a comunidade de veículos elétricos a crescer.</p>
                </div>
                
                <div class="cartao">
                    <div class="icone-destaque">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3>Sustentabilidade</h3>
                    <p>Contribua para um futuro mais sustentável apoiando a mobilidade elétrica e reduzindo a emissão de carbono.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Chamada para Ação -->
    <section class="secao secao-cinza">
        <div class="container" style="text-align: center;">
            <h2>Pronto para começar?</h2>
            <p>Junte-se à nossa comunidade e contribua para uma rede de carregamento mais ampla e acessível.</p>
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <a href="pontos.php" class="botao botao-verde botao-grande">
                    <i class="fas fa-bolt"></i> Ver Pontos de Carregamento
                </a>
            <?php else: ?>
                <a href="cadastro.php" class="botao botao-verde botao-grande">
                    <i class="fas fa-user-plus"></i> Criar Conta Gratuita
                </a>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>