<?php
include 'config.php';

// Verifica se o ID foi passado via GET
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Consulta ao banco de dados para pegar os dados do produto
    $sql = "SELECT * FROM produtos WHERE produto_id = ?";
    $stmt = $conn->prepare($sql);  // Alteração aqui: de $pdo para $conn
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC); // Obtém os dados do produto
} else {
    // Caso o ID não tenha sido passado, redireciona ou exibe uma mensagem de erro
    echo "ID não fornecido!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Atualizar Produto</title>
    <link rel="stylesheet" href="src/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script> 
    <style>
        :root {
            --bg-purple: #4A148C;
            --deep-purple: #3c096c;
            --text-white: #fff;
            --border-color: #ddd;
            --action-edit: #4CAF50;
            --action-delete: #E53935;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
        }
        .container {
            width: 80%;
            max-width: 1200px;
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            margin-bottom: 20px;
        }
        h2 {
            font-weight: 500;
            color: white;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid var(--border-color);
        }
        th, td {
            padding: 14px;
            text-align: center;
        }
        th {
            background-color: var(--bg-purple);
            color: var(--text-white);
        }
        td {
            background-color: #f9f9f9;
        }
        .actions {
            display: flex;
            justify-content: center;
            gap: 8px;
        }
        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            color: var(--text-white);
            font-size: 1.2rem;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .action-btn:hover {
            transform: scale(1.1);
        }
        .edit-btn {
            background-color: var(--action-edit);
        }
        .delete-btn {
            background-color: var(--action-delete);
        }
        .back-button {
            padding: 8px 30px;
            font-size: 1rem;
            color: white;
            background-color: #888;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }
        .back-button:hover {
            background-color: #666;
        }
        .btn-primary {
            margin-top: 2%;
            margin-left: 0%;
            width: 100%;
            background-color: var(--bg-purple);
            border-color: var(--deep-purple);
        }

        .btn-primary:hover {
            background-color: var(--deep-purple);
            border-color: var(--bg-purple);
        }
    </style>
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
</head>


    <div class="container">
        <h2 class="mt-5">Atualizar Produto</h2>
        <!-- Formulário para atualizar o produto -->
        <form action="produtos.php" method="post">
            <!-- Campo oculto com o ID -->
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['produto_id']); ?>">

            <div class="form-group">
                <label for="productName">Nome do Produto</label>
                <input type="text" name="productName" class="form-control" id="productName" value="<?php echo htmlspecialchars($row['nome']); ?>" required>
            </div>

            <div class="form-group">
                <label for="productDescription">Descrição</label>
                <textarea name="productDescription" class="form-control" id="productDescription" rows="3" required><?php echo htmlspecialchars($row['descricao']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="productCategory">Categoria</label>
                <select name="productCategory" class="form-control" id="productCategory" required>
                    <!-- Preencher com as categorias disponíveis -->
                    <?php
                        $categories = $conn->query("SELECT * FROM categorias")->fetchAll();
                        foreach ($categories as $category) {
                            $selected = ($category['categoria_id'] == $row['categoria_id']) ? 'selected' : '';
                            echo "<option value='{$category['categoria_id']}' {$selected}>{$category['nome']}</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="productPriceSale">Preço de Venda</label>
                <input type="text" name="productPriceSale" class="form-control" id="productPriceSale" value="<?php echo number_format($row['preco_venda'], 2, ',', '.'); ?>" required>
            </div>

            <div class="form-group">
                <label for="productPriceCost">Preço de Custo</label>
                <input type="text" name="productPriceCost" class="form-control" id="productPriceCost" value="<?php echo number_format($row['preco_custo'], 2, ',', '.'); ?>" required>
            </div>

            <div class="form-group">
                <label for="productStockQuantity">Quantidade em Estoque</label>
                <input type="number" name="productStockQuantity" class="form-control" id="productStockQuantity" value="<?php echo htmlspecialchars($row['quantidade_estoque']); ?>" required>
            </div>

            <div class="form-group">
                <label for="productUnit">Unidade de Medida</label>
                <input type="text" name="productUnit" class="form-control" id="productUnit" value="<?php echo htmlspecialchars($row['unidade_medida']); ?>" required>
            </div>

            <div class="form-group">
                <label for="productSupplier">Fornecedor</label>
                <select name="productSupplier" class="form-control" id="productSupplier" required>
                    <?php
                        $suppliers = $conn->query("SELECT * FROM fornecedores")->fetchAll();
                        foreach ($suppliers as $supplier) {
                            $selected = ($supplier['fornecedor_id'] == $row['fornecedor_id']) ? 'selected' : '';
                            echo "<option value='{$supplier['fornecedor_id']}' {$selected}>{$supplier['nome']}</option>";
                        }
                    ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Atualizar Produto</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <script>
        $(document).ready(function() {
            // Aplica a máscara de preço de venda
            $('#productPriceSale').mask('#.##0,00', {reverse: true});

            // Aplica a máscara de preço de custo
            $('#productPriceCost').mask('#.##0,00', {reverse: true});
        });
    </script>
</body>
</html>
