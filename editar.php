<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'config.php';

$item = null;
$funcionarios = [];

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM manutencoes WHERE manutencao_id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt) {
        // Usando o método correto de fetch para PDO
        $item = $stmt->fetch(PDO::FETCH_ASSOC); 
    } else {
        echo "Erro ao buscar item: " . $stmt->errorInfo()[2];
    }
}

$funcQuery = "SELECT funcionario_id, nome FROM funcionarios";
$funcResult = $conn->query($funcQuery);

if ($funcResult) {
    while ($funcRow = $funcResult->fetch(PDO::FETCH_ASSOC)) {
        $funcionarios[] = $funcRow;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nome_equip = htmlspecialchars($_POST['nome_equip']);
    $descricao = htmlspecialchars($_POST['descricao']);
    $data_inicio = $_POST['data_inicio'];
    $data_termino = $_POST['data_termino'];
    $id_funcionario = intval($_POST['id_func']);
    $status = htmlspecialchars($_POST['status']);
    $statusPermitidos = ['quebrado', 'funcional'];

    if (!in_array($status, $statusPermitidos)) {
        echo "Erro: Status Inválido.";
        exit();
    }

    // Consultando o nome do funcionário
    $query_nome = "SELECT nome FROM funcionarios WHERE funcionario_id = :id_funcionario";
    $stmt_nome = $conn->prepare($query_nome);
    $stmt_nome->bindParam(':id_funcionario', $id_funcionario, PDO::PARAM_INT);
    $stmt_nome->execute();
    $nome_row = $stmt_nome->fetch(PDO::FETCH_ASSOC);  
    $nome_func = $nome_row['nome'];
    
    // Atualizando o item
    $updateQuery = "UPDATE manutencoes SET equipamento = :nome_equip, descricao_problema = :descricao, data_inicio = :data_inicio, data_termino = :data_termino, tecnico_responsavel = :nome_func, status = :status, responsavel_id = :id_funcionario WHERE manutencao_id = :id";
    $stmt_update = $conn->prepare($updateQuery);
    
    // Binding dos parâmetros
    $stmt_update->bindParam(':nome_equip', $nome_equip);
    $stmt_update->bindParam(':descricao', $descricao);
    $stmt_update->bindParam(':data_inicio', $data_inicio);
    $stmt_update->bindParam(':data_termino', $data_termino);
    $stmt_update->bindParam(':nome_func', $nome_func);
    $stmt_update->bindParam(':status', $status);
    $stmt_update->bindParam(':id_funcionario', $id_funcionario, PDO::PARAM_INT);
    $stmt_update->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt_update->execute()) {
        header('Location: index.php');
        exit();
    } else {
        echo "Erro ao atualizar item: " . $stmt_update->errorInfo()[2];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESTOQUE</title>
    <link rel="stylesheet" href="src/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
   </style>
   
</head>
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
                </div>
            </nav>

            <main role="main" class="container">
            <div class="container">
                    <h1 class="h2">Editar Item</h1>
                </div>
                
                <form method="POST" action="editar.php">
                    <input type="hidden" name="id" value="<?php echo isset($item['manutencao_id']) ? $item['manutencao_id'] : ''; ?>">

                    <div class="mb-3">
                        <label class="form-label">Equipamento:</label>
                        <textarea class="form-control" name="nome_equip" required><?php echo isset($item['equipamento']) ? htmlspecialchars($item['equipamento']) : ''; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descrição do Problema:</label>
                        <input type="text" class="form-control" name="descricao" value="<?php echo isset($item['descricao_problema']) ? htmlspecialchars($item['descricao_problema']) : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Data de Inicio:</label>
                        <input type="datetime-local" class="form-control" name="data_inicio" value="<?php echo isset($item['data_inicio']) ? htmlspecialchars($item['data_inicio']) : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Data de Termino:</label>
                        <input type="datetime-local" class="form-control" name="data_termino" value="<?php echo isset($item['data_termino']) ? htmlspecialchars($item['data_termino']) : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tecnico Responsável:</label>
                        <select class="form-select" name="id_func" required>
                            <?php foreach ($funcionarios as $funcionario): ?>
                                <option value="<?php echo $funcionario['funcionario_id']; ?>" <?php echo (isset($item['responsavel_id']) && $item['responsavel_id'] == $funcionario['funcionario_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($funcionario['nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status:</label>
                        <select class="form-control" name="status" required>
                            <option value="quebrado" <?php echo (isset($item['status']) && $item['status'] === 'quebrado') ? 'selected' : ''; ?>>Quebrado</option>
                            <option value="funcional" <?php echo (isset($item['status']) && $item['status'] === 'funcional') ? 'selected' : ''; ?>>Funcional</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </form>
            </main>
        </div>
    </div>
</body>
</html>
