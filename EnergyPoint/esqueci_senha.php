<?php
session_start();
include 'db.php';
include 'enviar_link_redefinicao_firebase.php';
include 'header.php';

$erro = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    if (empty($email)) {
        $erro = 'Por favor, preencha o email.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            $senha = $usuario['senha'];

            // Verifica se é usuário do Firebase
            if (is_null($senha) || $senha === '' || trim($senha) === '') {
                // Envia link de redefinição via Firebase
                $resposta = enviar_link_redefinicao_firebase($email);

                if (isset($resposta['error'])) {
                    $erro = "Erro ao enviar o e-mail: " . $resposta['error']['message'];
                } else {
                    $erro = "Se o e-mail estiver cadastrado, você receberá um link para redefinir sua senha.";
                }
            } else {
                // Usuário local
                $_SESSION['redefinir_email'] = $email;
                header('Location: redefinir_senha.php');
                exit;
            }
        } else {
            $erro = 'Se o e-mail estiver cadastrado, você receberá um link para redefinir sua senha.';
        }
    }
}
?>

<main class="secao">
  <div class="container">
    <div class="cartao" style="max-width: 500px; margin: 0 auto;">
      <h2 style="text-align: center;">Recuperar Senha</h2>
      <p style="text-align: center;">Informe o e-mail cadastrado para redefinir sua senha.</p>

      <?php if (!empty($erro)): ?>
          <div class="mensagem-erro"> <?php echo $erro; ?> </div>
      <?php endif; ?>

      <form action="" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

        <button type="submit" class="botao botao-verde" style="width: 100%; margin-top: 20px;">Continuar</button>
      </form>
    </div>
  </div>
</main>

<?php include 'footer.php'; ?>