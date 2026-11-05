-- FinControl - estrutura do banco de dados
-- Script limpo para criação das tabelas, sem dados pessoais ou financeiros.
-- Compatível com MySQL / MariaDB.

CREATE DATABASE IF NOT EXISTS fincontrol
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE fincontrol;

CREATE TABLE IF NOT EXISTS usuarios (
  id int(11) NOT NULL AUTO_INCREMENT,
  nome varchar(100) NOT NULL,
  email varchar(100) NOT NULL,
  senha varchar(255) NOT NULL,
  data_cadastro timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id),
  UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS receitas (
  id int(11) NOT NULL AUTO_INCREMENT,
  usuario_id int(11) DEFAULT NULL,
  descricao varchar(100) DEFAULT NULL,
  valor decimal(10,2) DEFAULT NULL,
  data_receita date DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS despesas (
  id int(11) NOT NULL AUTO_INCREMENT,
  usuario_id int(11) DEFAULT NULL,
  descricao varchar(100) DEFAULT NULL,
  valor decimal(10,2) DEFAULT NULL,
  data_despesa date DEFAULT NULL,
  categoria varchar(50) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
