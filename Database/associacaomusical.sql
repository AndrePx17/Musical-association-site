CREATE DATABASE IF NOT EXISTS BD_associacao DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE BD_associacao;

SET NAMES utf8mb4;

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

-- Tabela de Logs para Auditoria (Triggers)
CREATE TABLE IF NOT EXISTS TB_logs (
    id_log INT PRIMARY KEY AUTO_INCREMENT,
    mensagem VARCHAR(255),
    data_evento TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- View para simplificar a consulta de notícias com o nome do autor
CREATE OR REPLACE VIEW VW_noticias_com_autor AS
SELECT 
    n.id_noticia, 
    n.titulo, 
    n.resumo, 
    n.corpo, 
    n.imagem, 
    n.data_criacao, 
    u.username AS autor
FROM TB_noticias n
JOIN TB_users u ON n.user_id = u.id_user;

-- Trigger para registar quando uma notícia é removida
DELIMITER //
CREATE TRIGGER IF NOT EXISTS TR_noticia_removida
AFTER DELETE ON TB_noticias
FOR EACH ROW
BEGIN
    INSERT INTO TB_logs (mensagem)
    VALUES (CONCAT('Notícia removida: ', OLD.titulo, ' (ID: ', OLD.id_noticia, ')'));
END //
DELIMITER ;

INSERT INTO TB_tipo(tipo)
VALUES
("admin")