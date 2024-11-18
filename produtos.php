<?php
include 'config.php';

try {
    // Verifica se os campos obrigatórios estão presentes
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['productName'], $_POST['productDescription'], $_POST['productCategory'], $_POST['productPriceSale'], $_POST['productPriceCost'], $_POST['productStockQuantity'], $_POST['productUnit'], $_POST['productSupplier'])) {

        // Sanitização e captura dos dados
        $name = htmlspecialchars(trim($_POST['productName']));
        $description = htmlspecialchars(trim($_POST['productDescription']));
        $category_id = intval($_POST['productCategory']);
        $price_sale = floatval(str_replace(['.', ','], ['', '.'], $_POST['productPriceSale'])); // Remove ponto e vírgula para inserir no banco
        $price_cost = floatval(str_replace(['.', ','], ['', '.'], $_POST['productPriceCost']));
        $stock_quantity = intval($_POST['productStockQuantity']);
        $unit = htmlspecialchars(trim($_POST['productUnit']));
        $supplier_id = intval($_POST['productSupplier']);

        // Verifica se os valores são válidos
        if (empty($name) || empty($description) || $price_sale <= 0 || $price_cost <= 0 || $stock_quantity < 0 || empty($unit) || $category_id <= 0 || $supplier_id <= 0) {
            throw new Exception('Por favor, preencha todos os campos corretamente.');
        }

        // Prepara a consulta SQL
        $stmt = $conn->prepare("
            INSERT INTO produtos 
            (nome, descricao, categoria_id, preco_venda, preco_custo, quantidade_estoque, unidade_medida, fornecedor_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        // Executa a consulta
        if ($stmt->execute([$name, $description, $category_id, $price_sale, $price_cost, $stock_quantity, $unit, $supplier_id])) {
            echo json_encode(['success' => true, 'message' => 'Produto cadastrado com sucesso!']);
        } else {
            throw new Exception('Falha ao cadastrar o produto.');
        }

        exit; // Finaliza o script após a inserção
    }
} catch (Exception $e) {
    // Exibe mensagens de erro detalhadas para depuração
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}

// Lista categorias, fornecedores e produtos
$categories = $conn->query("SELECT * FROM categorias")->fetchAll();
$suppliers = $conn->query("SELECT * FROM fornecedores")->fetchAll();
$products = $conn->query("SELECT produtos.*, categorias.nome AS category_name, fornecedores.nome AS supplier_name
FROM produtos
JOIN categorias ON produtos.categoria_id = categorias.categoria_id
JOIN fornecedores ON produtos.fornecedor_id = fornecedores.fornecedor_id")->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <!--NÃO ALTERAR-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de produtos</title>
    <link rel="stylesheet" href="src/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="src/css/styles.css">
</head>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <style>
       
    :root {
        --bg-purple: #4A148C;
        --deep-purple: #3c096c;
        --text-white: #fff;
        --border-color: #ddd;
    }
    
    .container {
        width: 100%;
        max-width: 800px;
        padding: 20px;
        background-color: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        margin-bottom: 20px;
    }
    h2 {
        font-weight: 500;
        color: white;
        text-align: center;
    }
    form {
        display: flex;
        flex-direction: column;
        gap: 15px;
        align-items: center;
    }
    .form-group, .mb-3 {
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 600px;
        text-align: left;
    }
    input, select {
        padding: 10px;
        font-size: 1rem;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background-color: #f9f9f9;
        width: 100%;
    }
    button {
        padding: 12px;
        font-size: 1rem;
        color: var(--text-white);
        background-color: var(--bg-purple);
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: background-color 0.3s ease;
        width: 100%;
        max-width: 600px;
        margin-top: 20px;
    }
    button:hover {
        background-color: var(--deep-purple);
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        padding: 14px;
        text-align: center;
        border: 1px solid var(--border-color);
    }
    th {
        background-color: var(--bg-purple);
        color: var(--text-white);
    }
    td {
        background-color: #f9f9f9;
    }
    .btn-primary{
    margin-top: 2%;
    margin-left: 0%;
    width: 100%;
    background-color: var(--bg-purple);
    border-color: var(--depp-purple);
}

.btn-primary:hover{
    background-color: var(--depp-purple);
    border-color: var(--bg-purple);
}
</style>
   
<body>
    <!--NÃO ALTERAR-->
    <body>
    <div class="container-fluid">
    <div class="row">
    <nav class="col-md-2 d-none d-md-block bg-purple sidebar">
                <div class="logo text-center py-4">
                   
                    <h2>HORIZON+</h2>
                </div>
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="produtos.php">
                               
                                <i class="bi bi-phone-fill icon-large"></i>
                                <span>Produtos</span>
                             
                            </a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="pedidos.php"><i class="bi bi-basket2-fill icon-large"></i> Pedidos</a></li>
                        <li class="nav-item"><a class="nav-link" href="fornecedores.php"><i class="bi bi-people-fill icon-large"></i> Fornecedores</a></li>
                        <li class="nav-item"><a class="nav-link" href="funcionarios.php"><i class="bi bi-person-badge-fill icon-large"></i> Funcionários</a></li>
                        <li class="nav-item"><a class="nav-link" href="estoque.php"><i class="bi bi-box-fill icon-large"></i> Estoque</a></li>
                        <li class="nav-item"><a class="nav-link" href="manutencao.php"><i class="bi bi-pc-display-horizontal icon-large"></i> Manutenção</a></li>
                    </ul>
                    <div class="user mt-4">
                        <div class="nav-item text-center">
                            <div class=>
                                
                                <div class="ml-2">
                                 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

        <main role="main" class="col-md-10 ml-sm-auto px-4">
            <div class="container">
                <div class="container"><h1 class="h2">Cadastro de Produtos</h1></div>
                
                
           

       
                <form id="productForm" method="POST">
                    <!-- Formulário de cadastro -->
                    <div class="form-group">
                        <label for="productName">Nome do Produto</label>
                        <input type="text" class="form-control" id="productName" name="productName" placeholder="Nome completo do produto" maxlength="100" required>
                    </div>
                    <div class="form-group">
                        <label for="productDescription">Descrição</label>
                        <textarea class="form-control" id="productDescription" name="productDescription" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="productCategory">Categoria do Produto</label>
                        <select class="form-control" id="productCategory" name="productCategory" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['categoria_id'] ?>"><?= htmlspecialchars($category['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="productPriceSale">Preço de Venda</label>
                        <input type="text" class="form-control" id="productPriceSale" name="productPriceSale" required>
                    </div>
                    <div class="form-group">
                        <label for="productPriceCost">Preço de Custo</label>
                        <input type="text" class="form-control" id="productPriceCost" name="productPriceCost" required>
                    </div>
                    <div class="form-group">
                        <label for="productStockQuantity">Quantidade em Estoque</label>
                        <input type="number" class="form-control" id="productStockQuantity" name="productStockQuantity" required>
                    </div>
                    <div class="form-group">
                        <label for="productUnit">Unidade de Medida</label>
                        <input type="text" class="form-control" id="productUnit" name="productUnit" required>
                    </div>
                    <div class="form-group">
                        <label for="productSupplier">Fornecedor</label>
                        <select class="form-control" id="productSupplier" name="productSupplier" required>
                            <?php foreach ($suppliers as $supplier): ?>
                                <option value="<?= $supplier['fornecedor_id'] ?>"><?= htmlspecialchars($supplier['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Cadastrar Produto</button>
                </form>

                <div id="productList" class="mt-4">
                    <h3>Lista de Produtos</h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Categoria</th>
                                <th>Fornecedor</th>
                                <th>Preço de Venda</th>
                                <th>Preço de Custo</th>
                                <th>Quantidade</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?= $product['produto_id'] ?></td>
                                    <td><?= htmlspecialchars($product['nome']) ?></td>
                                    <td><?= htmlspecialchars($product['category_name']) ?></td>
                                    <td><?= htmlspecialchars($product['supplier_name']) ?></td>
                                    <td><?= number_format($product['preco_venda'], 2, ',', '.') ?></td>
                                    <td><?= number_format($product['preco_custo'], 2, ',', '.') ?></td>
                                    <td><?= $product['quantidade_estoque'] ?></td>
                                    <td>
                                    <a href="update_produto.php?id=<?= $product['produto_id'] ?>" class="btn btn-warning btn-sm">Alterar</a>
                                    <a href="delete_produto.php?id=<?= $product['produto_id'] ?>" class="btn btn-danger btn-sm">Excluir</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Máscara para campos de preço e quantidade
        $('#productPriceSale').mask('000.000.000.000.000,00', {reverse: true});
        $('#productPriceCost').mask('000.000.000.000.000,00', {reverse: true});

        // Validação do formulário
        $('#productForm').submit(function(e) {
            e.preventDefault(); // Previne o envio padrão do formulário

            // Aqui você pode adicionar verificações adicionais de campo, por exemplo:
            var isValid = true;
            $('input, select').each(function() {
                if ($(this).val() == '') {
                    isValid = false;
                    alert('Por favor, preencha todos os campos obrigatórios.');
                }
            });

            if (isValid) {
                // Envia o formulário via AJAX, se estiver tudo certo
                $.ajax({
                    type: 'POST',
                    url: '', // A URL para processar o formulário (a mesma página)
                    data: $(this).serialize(),
                    success: function(response) {
                        var res = JSON.parse(response);
                        if (res.success) {
                            $('#confirmationMessage').show(); // Exibe a confirmação
                            setTimeout(function() {
                                $('#confirmationMessage').fadeOut(); // Oculta a confirmação
                            }, 3000);
                        } else {
                            alert('Erro: ' + res.message);
                        }
                    }
                });
            }
        });
    });
</script>
