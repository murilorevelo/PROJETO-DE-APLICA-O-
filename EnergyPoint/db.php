<?php
// Configurações do banco de dados
$host = 'localhost';
$dbname = 'energypoint';
$username = 'root';
$password = '';

// Inicialização do PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// Iniciar sessão para controle de login (somente se ainda não foi iniciada)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Buscar usuário pelo email (sem verificar senha)
function buscar_usuario_por_email($email) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return false;
    }
}

// Cadastrar usuário vindo do Firebase (com UID, sem senha)
function cadastrar_usuario_firebase($uid, $nome, $email) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (uid, nome, email) VALUES (?, ?, ?)");
        $stmt->execute([$uid, $nome, $email]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Atualizar nome do usuário pelo e-mail
function atualizar_nome_usuario_por_email($email, $novo_nome) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE usuarios SET nome = ? WHERE email = ?");
        $stmt->execute([$novo_nome, $email]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Funções para manipulação de pontos
function listar_pontos() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT id, nome, cep, numero, complemento, horario_funcionamento, lat, lng, usuario_id, data_cadastro FROM pontos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

function obter_ponto($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM pontos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return false;
    }
}

function adicionar_ponto_com_latlng($nome, $cep, $numero, $complemento, $horario_funcionamento, $usuario_id, $lat, $lng) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO pontos (nome, cep, numero, complemento, horario_funcionamento, usuario_id, lat, lng) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        // Assegure-se de que os nomes das colunas (latitude, longitude) estão corretos no seu BD
        $stmt->execute([$nome, $cep, $numero, $complemento, $horario_funcionamento, $usuario_id, $lat, $lng]);
        return true;
    } catch (PDOException $e) {
        // Para depuração, você pode logar o erro:
        error_log("Erro ao adicionar ponto: " . $e->getMessage());
        return false;
    }
}


function atualizar_ponto($id, $nome, $numero, $complemento, $cep, $horario_funcionamento) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE pontos SET nome = ?, cep = ?, numero = ?, complemento = ?,horario_funcionamento = ? WHERE id = ?");
        $stmt->execute([$nome, $cep, $numero, $complemento, $horario_funcionamento, $id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function atualizar_ponto_com_latlng($id, $nome, $numero, $complemento, $cep, $horario_funcionamento, $lat, $lng) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE pontos SET nome = ?, cep = ?, numero = ?, complemento = ?, horario_funcionamento = ?, lat = ?, lng = ? WHERE id = ?");
        $stmt->execute([$nome, $cep, $numero, $complemento, $horario_funcionamento, $lat, $lng, $id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function excluir_ponto($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM pontos WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}
?>