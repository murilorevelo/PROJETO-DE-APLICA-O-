<?php 
include 'db.php';
include 'header.php'; 
?>

<main class="secao">
    <div class="container">
        <div class="politicas">
            <h2>Políticas e Termos</h2>
            
            <!-- Política de Privacidade -->
            <h3 class="politica-titulo">Política de Privacidade</h3>
            <p>Última atualização: <?php echo date('d/m/Y'); ?></p>
            
            <p>A EletricPonto está comprometida em proteger sua privacidade. Esta Política de Privacidade explica como coletamos, usamos e protegemos suas informações pessoais quando você utiliza nossa plataforma.</p>
            
            <h4>1. Informações que coletamos</h4>
            <p>Coletamos as seguintes informações pessoais:</p>
            <ul>
                <li>Nome completo</li>
                <li>Endereço de e-mail</li>
                <li>CEP</li>
                <li>Informações sobre pontos de carregamento que você adiciona</li>
            </ul>
            
            <h4>2. Como usamos suas informações</h4>
            <p>Utilizamos suas informações para:</p>
            <ul>
                <li>Criar e gerenciar sua conta</li>
                <li>Permitir que você adicione e gerencie pontos de carregamento</li>
                <li>Melhorar nossos serviços</li>
                <li>Enviar comunicações importantes sobre a plataforma</li>
            </ul>
            
            <h4>3. Compartilhamento de informações</h4>
            <p>Não compartilhamos suas informações pessoais com terceiros, exceto:</p>
            <ul>
                <li>Quando exigido por lei</li>
                <li>Para proteger nossos direitos</li>
                <li>Com seu consentimento explícito</li>
            </ul>
            
            <!-- Política de Cookies -->
            <h3 class="politica-titulo">Política de Cookies</h3>
            <p>Última atualização: <?php echo date('d/m/Y'); ?></p>
            
            <p>Esta Política de Cookies explica como a EletricPonto utiliza cookies e tecnologias semelhantes para reconhecê-lo quando você visita nossa plataforma.</p>
            
            <h4>1. O que são cookies?</h4>
            <p>Cookies são pequenos arquivos de texto que são armazenados em seu dispositivo quando você visita um site. Eles são amplamente utilizados para fazer os sites funcionarem de maneira mais eficiente e fornecer informações aos proprietários do site.</p>
            
            <h4>2. Como usamos cookies</h4>
            <p>Utilizamos cookies para:</p>
            <ul>
                <li>Manter você conectado durante sua visita</li>
                <li>Lembrar suas preferências e configurações</li>
                <li>Melhorar a velocidade e segurança do site</li>
                <li>Analisar como nossa plataforma é utilizada</li>
            </ul>
            
            <h4>3. Tipos de cookies que utilizamos</h4>
            <ul>
                <li><strong>Cookies essenciais:</strong> Necessários para o funcionamento básico do site</li>
                <li><strong>Cookies de preferências:</strong> Permitem que o site lembre suas escolhas</li>
                <li><strong>Cookies analíticos:</strong> Nos ajudam a entender como os visitantes interagem com o site</li>
            </ul>
            
            <!-- Termos de Uso -->
            <h3 class="politica-titulo">Termos de Uso</h3>
            <p>Última atualização: <?php echo date('d/m/Y'); ?></p>
            
            <p>Bem-vindo à EletricPonto. Ao acessar ou usar nossa plataforma, você concorda com estes Termos de Uso. Por favor, leia-os cuidadosamente.</p>
            
            <h4>1. Aceitação dos Termos</h4>
            <p>Ao criar uma conta ou utilizar qualquer parte da plataforma EletricPonto, você concorda com estes Termos de Uso. Se você não concordar com qualquer parte destes termos, não poderá acessar ou utilizar nossos serviços.</p>
            
            <h4>2. Cadastro e Conta</h4>
            <p>Para utilizar determinados recursos da plataforma, você precisará criar uma conta. Você é responsável por manter a confidencialidade de suas credenciais de login e por todas as atividades que ocorrerem em sua conta.</p>
            
            <h4>3. Conteúdo do Usuário</h4>
            <p>Ao adicionar pontos de carregamento ou outras informações à plataforma, você concede à EletricPonto uma licença mundial, não exclusiva e isenta de royalties para usar, reproduzir e exibir esse conteúdo em conexão com os serviços.</p>
            
            <h4>4. Conduta do Usuário</h4>
            <p>Você concorda em não:</p>
            <ul>
                <li>Adicionar informações falsas ou enganosas</li>
                <li>Violar leis ou regulamentos aplicáveis</li>
                <li>Interferir no funcionamento da plataforma</li>
                <li>Tentar acessar áreas restritas sem autorização</li>
            </ul>
        </div>

        <!-- Botão de voltar para a página inicial -->
        <a href="index.php" class="botao botao-verde botao-grande botao-central" style="margin-top: 30px;">
            <i class="fas fa-arrow-left"></i> Voltar para o início
        </a>
    </div>
</main>

<?php include 'footer.php'; ?>
