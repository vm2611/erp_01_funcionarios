<?php
include 'config.php';

// Estabelecer a conexão com o banco de dados
$pdo = new PDO('mysql:host=localhost;dbname=empresa', 'root', '');

// Verificar se o ID da movimentação foi passado na URL
if (isset($_GET['id'])) {
    $estoque_id = $_GET['id'];

    // Consultar a movimentação de estoque existente
    $sql = "SELECT * FROM estoque WHERE estoque_id = :estoque_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':estoque_id', $estoque_id);
    $stmt->execute();
    $movimentacao = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se a movimentação não for encontrada, redirecionar
    if (!$movimentacao) {
        echo "Movimentação não encontrada!";
        exit;
    }
}

// Processar o formulário de atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coletar dados do formulário
    $produto_id = $_POST['produto_id'];
    $tipo_movimentacao = $_POST['tipo_movimentacao'];
    $quantidade = $_POST['quantidade'];
    $data_movimentacao = $_POST['data_movimentacao'];

    // Atualizar a movimentação no banco de dados
    $sql_update = "UPDATE estoque SET produto_id = :produto_id, tipo_movimentacao = :tipo_movimentacao, 
                   quantidade = :quantidade, data_movimentacao = :data_movimentacao 
                   WHERE estoque_id = :estoque_id";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->bindParam(':produto_id', $produto_id);
    $stmt_update->bindParam(':tipo_movimentacao', $tipo_movimentacao);
    $stmt_update->bindParam(':quantidade', $quantidade);
    $stmt_update->bindParam(':data_movimentacao', $data_movimentacao);
    $stmt_update->bindParam(':estoque_id', $estoque_id);

    if ($stmt_update->execute()) {
        echo "Movimentação de estoque atualizada com sucesso!";
        // Redirecionar de volta para a página de estoque
        header("Location: estoque.php");
        exit;
    } else {
        echo "Erro ao atualizar a movimentação!";
    }
}

// Buscar todos os produtos para preencher o campo select
$query_produtos = 'SELECT produto_id, nome FROM produtos';
$stmt_produtos = $pdo->query($query_produtos);
$result_produtos = $stmt_produtos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESTOQUE</title>
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
                                <i id="indicator" class="bi bi-caret-left-fill indicator"></i>
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

<body>
    <div class="container">
        <h1>Alterar Movimentação de Estoque</h1>
        <form method="POST" action="update.php?id=<?= $estoque_id ?>">
            <div class="form-group">
                <label for="produto_id">Produto</label>
                <select class="form-control" id="produto_id" name="produto_id" required>
                    <?php foreach ($result_produtos as $produto) { ?>
                        <option value="<?= $produto['produto_id'] ?>" <?= $movimentacao['produto_id'] == $produto['produto_id'] ? 'selected' : '' ?>><?= $produto['nome'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="tipo_movimentacao">Tipo de Movimentação</label>
                <select class="form-control" id="tipo_movimentacao" name="tipo_movimentacao" required>
                    <option value="x-produto" <?= $movimentacao['tipo_movimentacao'] == 'x-produto' ? 'selected' : '' ?>>Entrada</option>
                    <option value="y-produto" <?= $movimentacao['tipo_movimentacao'] == 'y-produto' ? 'selected' : '' ?>>Saída</option>
                    <option value="z-produto" <?= $movimentacao['tipo_movimentacao'] == 'z-produto' ? 'selected' : '' ?>>Ajuste</option>
                </select>
            </div>

            <div class="form-group">
                <label for="quantidade">Quantidade</label>
                <input type="number" class="form-control" id="quantidade" name="quantidade" value="<?= $movimentacao['quantidade'] ?>" required>
            </div>

            <div class="form-group">
                <label for="data_movimentacao">Data da Movimentação</label>
                <input type="datetime-local" class="form-control" id="data_movimentacao" name="data_movimentacao" value="<?= date('Y-m-d\TH:i', strtotime($movimentacao['data_movimentacao'])) ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Atualizar Movimentação</button>
        </form>
    </div>
</body>

</html>
