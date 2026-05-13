CREATE DATABASE IF NOT EXISTS BD_associacao;
USE BD_associacao;

CREATE TABLE IF NOT EXISTS TB_tipo(
    id_tipo INT UNIQUE PRIMARY KEY AUTO_INCREMENT,
    tipo VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS TB_users(
    id_user INT UNIQUE PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE,
    pass_user VARCHAR(255),
    tipo_id INT,
    FOREIGN KEY (tipo_id) REFERENCES TB_tipo(id_tipo)
);

CREATE TABLE IF NOT EXISTS TB_noticias(
    id_noticia INT UNIQUE PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(100),
    resumo VARCHAR(255),
    corpo TEXT,
    imagem VARCHAR(255),
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    foreign key (user_id) references TB_users(id_user)
);

CREATE TABLE IF NOT EXISTS TB_instrumentos(
    id_instrumento INT UNIQUE PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    codigo VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS TB_horarios (
    instrumento VARCHAR(100) NOT NULL PRIMARY KEY,
    imagem VARCHAR(255) NOT NULL,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
