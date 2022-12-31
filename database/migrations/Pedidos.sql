CREATE TABLE IF NOT EXISTS pedidos (
    id INTEGER NOT NULL AUTO_INCREMENT,
    descricao_pedido VARCHAR(255) NOT NULL,
    nome_comprador VARCHAR(255),
    cpf_comprador VARCHAR(20),
    created_at DATETIME NOT NULL,
    updated_at DATETIME,
    PRIMARY KEY (id)
);