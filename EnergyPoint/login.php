<?php 
include 'db.php';

// Incluir o cabeçalho
include 'header.php'; 
?>

<main class="secao">
    <div class="container">
        <div class="cartao" style="max-width: 500px; margin: 0 auto;">
            <h2 style="text-align: center;">Entrar na sua conta</h2>
            <p style="text-align: center; margin-bottom: 20px;">Ou <a href="cadastro.php">cadastre-se agora</a></p>
            
            <div id="mensagem-erro" class="mensagem-erro" style="display:none;"></div>
            
            <form id="form-login" autocomplete="off">
                <div>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div>
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                
                <div style="text-align: right; margin-top: 10px;">
                    <a href="esqueci_senha.php">Esqueceu sua senha?</a>
                </div>
                
                <button type="submit" class="botao botao-verde" style="width: 100%; margin-top: 20px;">Entrar</button>
            </form>

            <!-- Botão Google abaixo do formulário -->
            <div style="text-align: center; margin-top: 20px;">
                <button type="button" onclick="loginComGoogle()" class="botao botao-google" style="background: #fff; color: #444; border: 1px solid #ccc; width: 100%; padding: 10px;">
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" style="height:20px; vertical-align:middle; margin-right:8px;">
                    Entrar com Google
                </button>
            </div>
        </div>
    </div>
</main>

<!-- Firebase Login Script -->
<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.2/firebase-app.js";
import { getAuth, signInWithEmailAndPassword, GoogleAuthProvider, signInWithPopup } from "https://www.gstatic.com/firebasejs/9.22.2/firebase-auth.js";

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
const provider = new GoogleAuthProvider();

window.loginComGoogle = function() {
  mostrarErro(''); // limpa mensagem anterior
  signInWithPopup(auth, provider)
    .then((result) => result.user.getIdToken())
    .then((idToken) => {
      // Envia o idToken para o backend
      fetch('api_login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ idToken })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'ok') {
          window.location.href = 'pontos.php';
        } else {
          mostrarErro('Erro no login: ' + data.mensagem);
        }
      });
    })
    .catch((error) => {
      mostrarErro('Erro na autenticação: ' + error.message);
    });
}

// Login com e-mail e senha do Firebase
document.getElementById('form-login').addEventListener('submit', function(e) {
  e.preventDefault();

  mostrarErro(''); // limpa mensagens anteriores

  const email = document.getElementById('email').value;
  const senha = document.getElementById('senha').value;

  signInWithEmailAndPassword(auth, email, senha)
    .then((userCredential) => userCredential.user.getIdToken())
    .then((idToken) => {
      // Envia o idToken para o backend
      fetch('api_login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ idToken })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'ok') {
          window.location.href = 'pontos.php';
        } else {
          mostrarErro('Erro no login: ' + data.mensagem);
        }
      });
    })
    .catch((error) => {
      mostrarErro('Erro de login: ' + error.message);
    });
});

function mostrarErro(msg) {
  const div = document.getElementById('mensagem-erro');
  if (msg) {
    div.style.display = 'block';
    div.innerText = msg;
  } else {
    div.style.display = 'none';
    div.innerText = '';
  }
}
</script>

<?php include 'footer.php'; ?>