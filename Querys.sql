CREATE TABLE 'usuarios'(
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome varchar(50) NOT NULL,
    email varchar(250) NOT NULL,
    senha varchar(32) NOT NULL
    tipo_usuario int ENUM('1', '2') DEFAULT '1'
);

CREATE TABLE hamburguer (
  	id INT PRIMARY KEY AUTO_INCREMENT,
  	nome VARCHAR(255),
  	descricao TEXT,
  	valor DECIMAL(10,2) NOT NULL
);

CREATE TABLE ingredientes (
  	id INT PRIMARY KEY AUTO_INCREMENT,
  	nome VARCHAR(255)
);

CREATE TABLE hamburguer_ingredientes ( 
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
	hamburguer_id INT NOT NULL, 
	ingrediente_id INT NOT NULL, 
	FOREIGN KEY (hamburguer_id) REFERENCES hamburguer(id), 
	FOREIGN KEY (ingrediente_id) REFERENCES ingredientes(id) 
);

CREATE TABLE carrinho(
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_usuario int NOT NULL,
    id_hamburguer int NOT NULL,
    quantidade int NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_hamburguer) REFERENCES hamburguer(id)
);

CREATE TABLE pedidos(
	id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_usuario int NOT NULL,
    valor_total DECIMAL(10,2) NOT NULL,
    staus_pedido ENUM ('pendente', 'preparando', 'pronto', 'finalizado') DEFAULT 'pendente',
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

CREATE TABLE itens_pedido(
	id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_pedido int NOT NULL,
    id_hamburguer int NOT NULL,
    quantidade int NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id),
    FOREIGN KEY (id_hamburguer) REFERENCES hamburguer(id)
);


















