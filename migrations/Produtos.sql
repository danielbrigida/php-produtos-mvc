CREATE TABLE produtos (
     id INTEGER NOT NULL AUTO_INCREMENT,
     descricao VARCHAR(255) NOT NULL,
	 valor DECIMAL(10,2) NOT NULL,
	 estoque INTEGER NOT NULL,
     created_at DATETIME NOT NULL,
     updated_at DATETIME,
     PRIMARY KEY (id)
);