CREATE DATABASE IF NOT EXISTS empresa;
USE empresa;

CREATE TABLE IF NOT EXISTS fornecedores (
    fornecedor_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    endereco VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS setores (
    setor_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT
);

CREATE TABLE IF NOT EXISTS categorias (
    categoria_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT
);

CREATE TABLE IF NOT EXISTS produtos (
    produto_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    categoria_id INT NOT NULL,
    preco_venda DECIMAL(10,2),
    preco_custo DECIMAL(10,2),
    quantidade_estoque INT,
    unidade_medida VARCHAR(50),
    fornecedor_id INT NOT NULL,
    FOREIGN KEY (categoria_id) REFERENCES categorias(categoria_id),
    FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(fornecedor_id)
);

CREATE TABLE IF NOT EXISTS estoque (
    estoque_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    produto_id INT NOT NULL,
    tipo_movimentacao ENUM('x-produto','y-produto','z-produto') NOT NULL,
    quantidade INT NOT NULL,
    data_movimentacao DATETIME,
    FOREIGN KEY (produto_id) REFERENCES produtos(produto_id)
);


CREATE TABLE IF NOT EXISTS funcionarios (
    funcionario_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cargo VARCHAR(50) NOT NULL,
    setor_id INT NOT NULL,
    telefone VARCHAR(20),
    email VARCHAR(100) NOT NULL,
    data_admissao DATE NOT NULL,
    salario VARCHAR(20),
    metodo_pagamento VARCHAR(500),
    FOREIGN KEY (setor_id) REFERENCES setores(setor_id)
);

-- Adicionando a coluna 'metodo_pagamento_id' e a chave estrangeira apenas se não existirem
-- Adicionando a coluna 'metodo_pagamento_id' (sem IF NOT EXISTS)


CREATE TABLE IF NOT EXISTS manutencoes (
    manutencao_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    equipamento VARCHAR(100) NOT NULL,
    descricao_problema TEXT NOT NULL,
    data_inicio DATETIME NOT NULL,
    data_termino DATETIME NOT NULL,
    tecnico_responsavel VARCHAR(100),
    status ENUM('quebrado', 'funcional') NOT NULL,
    responsavel_id INT NOT NULL,
    FOREIGN KEY (responsavel_id) REFERENCES funcionarios(funcionario_id)
);

CREATE TABLE IF NOT EXISTS pedidos (
    pedido_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    data_pedido DATETIME NOT NULL,
    status ENUM('Ativo', 'Inativo', 'Suspenso') NOT NULL,
    valor_total DECIMAL(10,2),
    funcionario_id INT NOT NULL,
    FOREIGN KEY (funcionario_id) REFERENCES funcionarios(funcionario_id)
);

CREATE TABLE IF NOT EXISTS itens_pedidos (
    itempedido_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(pedido_id),
    FOREIGN KEY (produto_id) REFERENCES produtos(produto_id)
);

-- Inserir dados nas tabelas
-- Inserir setores
INSERT INTO setores (nome, descricao) VALUES
('Setor A', 'Responsável pela produção'),
('Setor B', 'Setor de logística e transporte'),
('Setor C', 'Departamento financeiro'),
('Setor D', 'Recursos humanos'),
('Setor E', 'Manutenção e reparos'),
('Setor F', 'Suporte ao cliente'),
('Setor G', 'Vendas e marketing');


-- Inserir categorias
INSERT INTO categorias (nome, descricao) VALUES
('Categoria A', 'Categoria de eletrônicos'),
('Categoria B', 'Categoria de materiais de escritório'),
('Categoria C', 'Categoria de produtos alimentícios'),
('Categoria D', 'Categoria de vestuário'),
('Categoria E', 'Categoria de móveis'),
('Categoria F', 'Categoria de cosméticos'),
('Categoria G', 'Categoria de produtos de limpeza'),
('Categoria H', 'Categoria de brinquedos'),
('Categoria I', 'Categoria de ferramentas'),
('Categoria J', 'Categoria de materiais esportivos'),
('Categoria K', 'Categoria de produtos automotivos'),
('Categoria L', 'Categoria de livros e publicações'),
('Categoria M', 'Categoria de medicamentos'),
('Categoria N', 'Categoria de jardinagem'),
('Categoria O', 'Categoria de acessórios eletrônicos'),
('Categoria P', 'Categoria de artigos para pets'),
('Categoria Q', 'Categoria de produtos de beleza'),
('Categoria R', 'Categoria de alimentos congelados');

-- Inserir fornecedores
INSERT INTO fornecedores (nome, telefone, email, endereco) VALUES
('Fornecedor A', '11999990000', 'fornecedorA@example.com', 'Rua 1, Nº 100'),
('Fornecedor B', '21999990001', 'fornecedorB@example.com', 'Rua 2, Nº 101'),
('Fornecedor C', '31999990002', 'fornecedorC@example.com', 'Rua 3, Nº 102'),
('Fornecedor D', '41999990003', 'fornecedorD@example.com', 'Rua 4, Nº 103'),
('Fornecedor E', '51999990004', 'fornecedorE@example.com', 'Rua 5, Nº 104'),
('Fornecedor F', '61999990005', 'fornecedorF@example.com', 'Rua 6, Nº 105'),
('Fornecedor G', '71999990006', 'fornecedorG@example.com', 'Rua 7, Nº 106'),
('Fornecedor H', '81999990007', 'fornecedorH@example.com', 'Rua 8, Nº 107'),
('Fornecedor I', '91999990008', 'fornecedorI@example.com', 'Rua 9, Nº 108'),
('Fornecedor J', '11999990009', 'fornecedorJ@example.com', 'Rua 10, Nº 109');

-- Inserir produtos
INSERT INTO produtos (nome, descricao, preco_venda, preco_custo, quantidade_estoque, unidade_medida, fornecedor_id, categoria_id) VALUES
('Produto A1', 'Descrição do Produto A1', 25.90, 15.00, 50, 'Unidade', 1, 1),
('Produto A2', 'Descrição do Produto A2', 15.75, 9.50, 100, 'Unidade', 2, 2),
('Produto A3', 'Descrição do Produto A3', 45.20, 30.00, 3, 'Unidade', 3, 3),
('Produto A4', 'Descrição do Produto A4', 120.00, 75.00, 25, 'Unidade', 4, 4),
('Produto A5', 'Descrição do Produto A5', 80.00, 60.00, 40, 'Unidade', 5, 5);

-- Inserir funcionários
INSERT INTO funcionarios (nome, cargo, setor_id, telefone, email, data_admissao, salario, metodo_pagamento) VALUES
('João Silva', 'Analista de Sistemas', 1, '(11) 9988-7766', 'joao.silva@empresa.com', '2023-01-10', 5000.00, 'Cartão de Crédito'),
('Maria Oliveira', 'Gerente de Vendas', 2, '(21) 9977-6655', 'maria.oliveira@empresa.com', '2023-02-15', 7000.00, 'Transferência Bancária'),
('Carlos Souza', 'Coordenador de Logística', 3, '(31) 9966-5544', 'carlos.souza@empresa.com', '2023-03-20', 5500.00, 'Boleto'),
('Ana Paula', 'Assistente Administrativo', 4, '(41) 9955-4433', 'ana.paula@empresa.com', '2023-04-25', 3000.00, 'Dinheiro'),
('Roberto Costa', 'Engenheiro de Produção', 5, '(51) 9944-3322', 'roberto.costa@empresa.com', '2023-05-30', 6000.00, 'Cartão de Débito');

-- Inserir manutenções
INSERT INTO manutencoes (equipamento, descricao_problema, data_inicio, data_termino, tecnico_responsavel, status, responsavel_id) VALUES 
('Máquina de Solda', 'Não liga após uso contínuo', '2024-02-01 08:00:00', '2024-02-03 17:00:00', 'Luís Almeida', 'funcional', 5), 
('Esteira de Produção', 'Correia desalinhada', '2024-02-04 09:00:00', '2024-02-05 14:00:00', 'Marcos Lima', 'funcional', 4), 
('Impressora 3D', 'Problemas na extrusão', '2024-02-07 10:00:00', '2024-02-08 18:00:00', 'Patrícia Santos', 'quebrado', 3), 
('Computador Desktop', 'Frequente desligamento', '2024-02-09 15:00:00', '2024-02-10 19:00:00', 'Rafael Oliveira', 'funcional', 2);

-- Inserir pedidos
INSERT INTO pedidos (data_pedido, status, valor_total, funcionario_id) VALUES
('2024-01-01 10:00:00', 'Ativo', 2000.50, 1),
('2024-01-02 11:00:00', 'Inativo', 1500.75, 3),
('2024-01-03 14:00:00', 'Suspenso', 2200.00, 5),
('2024-01-04 15:00:00', 'Ativo', 5000.00, 4),
('2024-01-05 16:00:00', 'Suspenso', 800.00, 2);

-- Inserir itens de pedidos
INSERT INTO itens_pedidos (pedido_id, produto_id, quantidade) VALUES
(1, 1, 5),
(1, 2, 2),
(2, 3, 3),
(3, 4, 1),
(4, 5, 2);
