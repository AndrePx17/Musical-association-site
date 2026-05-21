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
    nome VARCHAR(100) NOT NULL UNIQUE
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

-- View para gerir o estado dos horários por instrumento
CREATE OR REPLACE VIEW VW_gestao_horarios AS
SELECT 
    i.id_instrumento,
    i.nome AS instrumento,
    h.data_atualizacao,
    IF(h.imagem IS NULL OR h.imagem = '', 'Sem Horário', 'Atualizado') AS estado
FROM TB_instrumentos i
LEFT JOIN TB_horarios h ON i.nome = h.instrumento;

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
