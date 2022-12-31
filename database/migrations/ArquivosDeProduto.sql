CREATE TABLE IF NOT EXISTS arquivos_de_produtos (
    id INTEGER NOT NULL AUTO_INCREMENT,
    produto_id INTEGER NOT NULL,
    path_file VARCHAR(255) NOT NULL,
    nome_unico VARCHAR(255) NOT NULL,
    nome_original VARCHAR(255) NOT NULL,
    hash VARCHAR(255),
    created_at DATETIME NOT NULL,
    updated_at DATETIME,
    PRIMARY KEY (id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id),
    UNIQUE INDEX unique_nome_unico(nome_unico) 
);