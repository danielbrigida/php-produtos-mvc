CREATE TABLE IF NOT EXISTS itens_de_pedidos (
    id INTEGER NOT NULL AUTO_INCREMENT,
    produto_id INTEGER NOT NULL,
    pedido_id INTEGER NOT NULL,
    valor DECIMAL(12,2) NOT NULL,
	quantidade INTEGER NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME,
    PRIMARY KEY (id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id),
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
);