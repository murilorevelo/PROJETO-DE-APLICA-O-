<?php include 'header.php'; ?>
<main class="secao">
    <div class="container">
        <div class="cartao" style="max-width: 500px; margin: 0 auto;">
            <h2 style="text-align: center;">Criar uma nova conta</h2>
            <p style="text-align: center; margin-bottom: 20px;">Ou <a href="login.php">entre na sua conta</a></p>

            <div id="mensagem" style="text-align: center;"></div>

            <form id="formCadastro">
                <div>
                    <label for="nome">Nome completo</label>
                    <input type="text" id="nome" name="nome" required>
                </div>

                <div>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div>
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" required>
                </div>

                <div>
                    <label for="confirmar_senha">Confirmar senha</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" required>
                </div>

                <div>
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <input type="checkbox" id="termos" name="termos" required style="width: auto; margin: 0;">
                        <label for="termos" style="display: inline; margin: 0;">
                            Concordo com as <a href="politicas.php">Políticas de Privacidade</a> e <a href="politicas.php">Política de Cookies</a>
                        </label>
                    </div>
                </div>

                <button type="submit" class="botao botao-verde" style="width: 100%; margin-top: 20px;">Cadastrar</button>
            </form>
        </div>
    </div>
</main>

<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
import { getAuth, createUserWithEmailAndPassword, updateProfile } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";

// Pegue a chave do Firebase do PHP
const firebaseConfig = {
    apiKey: "<?php echo getenv('FIREBASE_API_KEY'); ?>",
    authDomain: "energypointfho.firebaseapp.com",
    projectId: "energypointfho",
    storageBucket: "energypointfho.firebasestorage.app",
    messagingSenderId: "195132119393",
    appId: "1:195132119393:web:011430cf71ac7ba3e18d20",
    measurementId: "G-H2XV7GS6LS"
};

const app = initializeApp(firebaseConfig);
const auth = getAuth(app);

document.getElementById('formCadastro').addEventListener('submit', async function (e) {
    e.preventDefault();

    const nome = document.getElementById('nome').value.trim();
    const email = document.getElementById('email').value.trim();
    const senha = document.getElementById('senha').value;
    const confirmarSenha = document.getElementById('confirmar_senha').value;
    const termos = document.getElementById('termos').checked;
    const mensagem = document.getElementById('mensagem');

    if (!termos) {
        mensagem.innerHTML = '<div class="mensagem-erro">Você precisa aceitar os termos.</div>';
        return;
    }

    if (senha !== confirmarSenha) {
        mensagem.innerHTML = '<div class="mensagem-erro">As senhas não coincidem.</div>';
        return;
    }

    try {
        // 1. Criar usuário no Firebase
        const cred = await createUserWithEmailAndPassword(auth, email, senha);
        const user = cred.user; // O objeto do usuário autenticado

        // 2. Definir o displayName do usuário no Firebase
        // Isso é crucial para o Firebase guardar o nome
        await updateProfile(user, {
            displayName: nome
        });

        // 3. Enviar dados para PHP (seu backend)
        // Isso é para o seu banco de dados MySQL, se você quiser manter uma cópia lá
        const resposta = await fetch('salvar_usuario.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ uid: user.uid, nome: nome, email: email }) // Envie o nome também
        });

        const json = await resposta.json();
        if (json.sucesso) {
            mensagem.innerHTML = '<div class="mensagem-sucesso">Cadastro realizado com sucesso!<br><a href="login.php" class="botao botao-azul">Fazer login</a></div>';
            document.getElementById('formCadastro').reset();
        } else {
            // Se houver um erro no backend, você pode reverter o usuário no Firebase ou lidar com isso.
            // Para simplicidade, aqui apenas mostramos o erro.
            mensagem.innerHTML = '<div class="mensagem-erro">' + json.erro + '</div>';
        }
    } catch (err) {
        // Lidar com erros do Firebase Authentication
        if (err.code === 'auth/email-already-in-use') {
            mensagem.innerHTML = '<div class="mensagem-erro">Este e-mail já está cadastrado. <a href="login.php">Faça login</a> ou <a href="esqueci_senha.php">redefina sua senha</a>.</div>';
        } else if (err.code === 'auth/weak-password') {
            mensagem.innerHTML = '<div class="mensagem-erro">A senha deve ter pelo menos 6 caracteres.</div>';
        } else {
            mensagem.innerHTML = '<div class="mensagem-erro">Erro no Firebase: ' + err.message + '</div>';
        }
    }
});
</script>

<?php include 'footer.php'; ?>