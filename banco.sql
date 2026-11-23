-- Banco e tabela de disciplinas
-- Importe no phpMyAdmin: aba "Importar" -> este arquivo -> "Executar"
CREATE DATABASE IF NOT EXISTS daw CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE daw;

CREATE TABLE IF NOT EXISTS disciplinas (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    sigla         VARCHAR(10)  NOT NULL UNIQUE,
    nome          VARCHAR(100) NOT NULL,
    carga_horaria INT          NOT NULL
);
