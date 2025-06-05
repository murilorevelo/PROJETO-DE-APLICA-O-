<?php 
include 'db.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Inicializar variáveis
$erro = '';
$nome = '';
$cep = '';
$numero = '';
$complemento = '';
$horario_funcionamento = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $cep = $_POST['cep'] ?? '';
    $numero = $_POST['numero'] ?? '';
    $complemento = $_POST['complemento'] ?? '';
    $horario_funcionamento = $_POST['horario_funcionamento'] ?? '';
    $usuario_id = $_SESSION['usuario_id'];

    if (empty($nome) || empty($cep) || empty($numero) || empty($complemento)|| empty($horario_funcionamento)) {
        $erro = 'Todos os campos são obrigatórios.';
    } else {
        // Obter lat/lng via Google Maps API
        $cep_formatado = urlencode($cep);
        $api_key = getenv('Maps_API_KEY');
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$cep_formatado}&key={$api_key}";

        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if ($data['status'] === 'OK') {
            $location = $data['results'][0]['geometry']['location'];
            $lat = $location['lat'];
            $lng = $location['lng'];

            // Salvar no banco
            $sucesso = adicionar_ponto_com_latlng($nome, $cep, $numero, $complemento, $horario_funcionamento, $usuario_id, $lat, $lng);

            if ($sucesso) {
                header('Location: pontos.php');
                exit;
            } else {
                $erro = 'Erro ao adicionar o ponto. Tente novamente.';
            }
        } else {
            $erro = 'Não foi possível obter a localização a partir do CEP.';
        }
    }
}

// Incluir o cabeçalho
include 'header.php'; 
?>

<main class="secao">
    <div class="container">
        <div class="cartao" style="max-width: 600px; margin: 0 auto;">
            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <a href="pontos.php" class="botao botao-azul botao-pequeno" style="margin-right: 15px;">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <h2 style="margin: 0;">Adicionar Ponto de Carregamento</h2>
            </div>
            
            <?php if (!empty($erro)): ?>
                <div class="mensagem-erro">
                    <?php echo $erro; ?>
                </div>
            <?php endif; ?>
            
            <form action="adicionar_ponto.php" method="post">
                <div>
                    <label for="nome">Nome do Ponto de Carregamento</label>
                    <input type="text" id="nome" name="nome" value="<?php echo $nome; ?>" required>
                </div>
                
                <div>
                    <label for="cep">CEP</label>
                    <input type="text" id="cep" name="cep" value="<?php echo $cep; ?>"placeholder="apenas número" required>
                </div>

                <div>
                    <label for="numero">Número</label>
                    <input type="text" id="numero" name="numero" value="<?php echo $numero; ?>" required>
                </div>

                <div>
                    <label for="complemento">Complemento</label>
                    <input type="text" id="complemento" name="complemento" value="<?php echo $complemento; ?>" required>
                </div>    
                 
                <div>
                    <label for="horario_funcionamento">Horário de Funcionamento</label>
                    <input type="text" id="horario_funcionamento" name="horario_funcionamento" value="<?php echo $horario_funcionamento; ?>" placeholder="Ex: Segunda a Sexta, 8h às 18h" required>
                </div>
                
                <div style="text-align: right;">
                    <button type="submit" class="botao botao-verde">Adicionar Ponto</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>