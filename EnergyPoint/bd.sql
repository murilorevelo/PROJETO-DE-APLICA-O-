-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS energypoint;
USE energypoint;

-- Tabela de usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uid VARCHAR(128) UNIQUE,       
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255),              
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);
-- Tabela de pontos de carregamento
CREATE TABLE IF NOT EXISTS pontos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cep VARCHAR(10) NOT NULL,
    numero VARCHAR(10) NOT NULL,
    complemento VARCHAR(100),
    horario_funcionamento VARCHAR(100) NOT NULL,
    usuario_id INT,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    lat DECIMAL(10, 8),
    lng DECIMAL(11, 8),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);