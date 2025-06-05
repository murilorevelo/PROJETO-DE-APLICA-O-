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
$ponto = obter_ponto($id);

// Verificar se o ponto existe e pertence ao usuário
if (!$ponto || $ponto['usuario_id'] != $_SESSION['usuario_id']) {
    header('Location: pontos.php');
    exit;
}

$erro = '';

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $cep = $_POST['cep'] ?? '';
    $numero = $_POST['numero'] ?? '';
    $complemento = $_POST['complemento'] ?? '';
    $horario_funcionamento = $_POST['horario_funcionamento'] ?? '';

    if (empty($nome)) {
        $erro = 'O nome do ponto é obrigatório.';
    } elseif (empty($cep)) {
        $erro = 'O CEP é obrigatório.';
    } elseif (empty($numero)) {
        $erro = 'O número é obrigatório.';
    } elseif (empty($complemento)) {
        $erro = 'O complemento é obrigatório.';
    } elseif (empty($horario_funcionamento)) {
        $erro = 'O horário de funcionamento é obrigatório.';
    } else {
        // Verificar se o CEP foi alterado
        if ($cep !== $ponto['cep']) {
            // Buscar nova lat/lng via Google Maps API
            $cep_formatado = urlencode($cep);
            $api_key = getenv('Maps_API_KEY'); // Usa a variável de ambiente
            $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$cep_formatado}&key={$api_key}";

            $response = file_get_contents($url);
            $data = json_decode($response, true);

            if ($data['status'] === 'OK') {
                $location = $data['results'][0]['geometry']['location'];
                $lat = $location['lat'];
                $lng = $location['lng'];

                // Atualizar com latitude e longitude
                $sucesso = atualizar_ponto_com_latlng($id, $nome, $numero, $complemento, $cep, $horario_funcionamento, $lat, $lng);
            } else {
                $erro = 'Não foi possível obter a nova localização a partir do CEP.';
            }
        } else {
            // Atualizar normalmente sem mudar lat/lng
            $sucesso = atualizar_ponto($id, $nome, $numero, $complemento, $cep, $horario_funcionamento);
        }

        if (empty($erro) && $sucesso) {
            header('Location: pontos.php');
            exit;
        } elseif (empty($erro)) {
            $erro = 'Erro ao atualizar o ponto. Tente novamente.';
        }
    }
}

include 'header.php'; 
?>

<main class="secao">
    <div class="container">
        <div class="cartao" style="max-width: 600px; margin: 0 auto;">
            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <a href="pontos.php" class="botao botao-azul botao-pequeno" style="margin-right: 15px;">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <h2 style="margin: 0;">Editar Ponto de Carregamento</h2>
            </div>

            <?php if (!empty($erro)): ?>
                <div class="mensagem-erro">
                    <?php echo $erro; ?>
                </div>
            <?php endif; ?>

            <form action="editar_ponto.php?id=<?php echo $id; ?>" method="post">
                <div>
                    <label for="nome">Nome do Ponto de Carregamento</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($ponto['nome']); ?>" required>
                </div>

                <div>
                    <label for="cep">CEP</label>
                    <input type="text" id="cep" name="cep" value="<?php echo htmlspecialchars($ponto['cep']); ?>" required>
                </div>

                <div>
                    <label for="numero">Número</label>
                    <input type="text" id="numero" name="numero" value="<?php echo htmlspecialchars($ponto['numero']); ?>" required>
                </div>

                <div>
                    <label for="complemento">Complemento</label>
                    <input type="text" id="complemento" name="complemento" value="<?php echo htmlspecialchars($ponto['complemento']); ?>" required>
                </div>

                <div>
                    <label for="horario_funcionamento">Horário de Funcionamento</label>
                    <input type="text" id="horario_funcionamento" name="horario_funcionamento" value="<?php echo htmlspecialchars($ponto['horario_funcionamento']); ?>" required>
                </div>

                <div style="text-align: right;">
                    <button type="submit" class="botao botao-verde">Atualizar Ponto</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>