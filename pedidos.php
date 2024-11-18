<?php
// Verifica se há parâmetros na URL
if (!empty($_GET)) {
    var_dump($_GET);
}

include 'config.php';

// Consulta para buscar os pedidos
$sql_pedidos = "SELECT p.pedido_id, p.data_pedido, p.status, p.valor_total, f.nome AS nome_funcionario
                FROM pedidos p
                JOIN funcionarios f ON p.funcionario_id = f.funcionario_id";
$stmt_pedidos = $conn->prepare($sql_pedidos);
$stmt_pedidos->execute();
$result_pedidos = $stmt_pedidos->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Verifique se o pedido existe no banco de dados
    $sql_verificar = "SELECT COUNT(*) FROM pedidos WHERE pedido_id = :id";
    $stmt_verificar = $conn->prepare($sql_verificar);
    $stmt_verificar->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt_verificar->execute();
    
    if ($stmt_verificar->fetchColumn() == 0) {
        exit();
    }

    // Proseguir com o código de exclusão...
} else {
    // Caso o 'id' não seja encontrado na URL, o que fazer
    // Aqui você pode definir um comportamento adicional se necessário
}

// Verifica se a consulta foi bem-sucedida
if ($result_pedidos === false) {
    echo "Erro ao buscar pedidos: " . $stmt_pedidos->errorInfo()[2];
    exit();
}

// Consulta para buscar os funcionários
$sql_funcionarios = "SELECT funcionario_id, nome FROM funcionarios";
$stmt_funcionarios = $conn->prepare($sql_funcionarios);
$stmt_funcionarios->execute();
$result_funcionarios = $stmt_funcionarios->fetchAll(PDO::FETCH_ASSOC);

// Fecha a conexão
$conn = null;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <!--NÃO ALTERAR-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Pedidos</title>
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
            <main role="main" class="container">
                <div class="container">
                    <h1 class="h2">CADASTRO DE PEDIDOS</h1>
                </div>

                <!-- Formulário de Cadastro -->
                <form method="POST" action="create.php">
                    <div class="mb-3">
                        <label for="data_pedido" class="form-label">Data e Hora do Pedido</label>
                        <input type="datetime-local" name="data_pedido" class="form-control" id="data_pedido" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status do Pedido</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Ativo">Ativo</option>
                            <option value="Inativo">Inativo</option>
                            <option value="Suspenso">Suspenso</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="valor_total" class="form-label">Valor Total do Pedido</label>
                        <input type="text" class="form-control" id="valor_total" name="valor_total" required>
                    </div>
                    <div class="mb-3">
                        <label for="funcionario_responsavel" class="form-label">Funcionário Responsável</label>
                        <select class="form-control" id="funcionario_responsavel" name="funcionario_id" required>
                            <?php foreach ($result_funcionarios as $funcionario) { ?>
                                <option value="<?= $funcionario['funcionario_id'] ?>"><?= htmlspecialchars($funcionario['nome']) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Cadastrar Pedido</button>
                </form>

                <!-- Listagem de Pedidos Cadastrados -->
                <h2 class="mt-5">Pedidos Cadastrados</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Data do Pedido</th>
                            <th>Status</th>
                            <th>Valor Total</th>
                            <th>Funcionário Responsável</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result_pedidos as $pedido) { ?>
                            <tr>
                                <td><?= htmlspecialchars($pedido['data_pedido']) ?></td>
                                <td><?= htmlspecialchars($pedido['status']) ?></td>
                                <td>R$ <?= number_format($pedido['valor_total'], 2, ',', '.') ?></td>
                                <td><?= htmlspecialchars($pedido['nome_funcionario']) ?></td>
                                <td>
                                    <a href="update_pedidos.php?id=<?= $pedido['pedido_id'] ?>" class="btn btn-warning btn-sm">Alterar</a>
                                    <a href="delete_pedidos.php?id=<?= $pedido['pedido_id'] ?>" class="btn btn-danger btn-sm">Excluir</a>
                                    
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Aplica a máscara de moeda no campo de valor total
            $('#valor_total').mask('000.000.000,00', { reverse: true });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="src/js/script.js"></script>
</body>
</html>
