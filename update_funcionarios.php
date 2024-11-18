<?php
include 'config.php';

// Ativar a exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar se o ID foi passado na URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Buscar os dados do funcionário
    $sql = "SELECT * FROM funcionarios WHERE funcionario_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar se o funcionário foi encontrado
    if (!$row) {
        echo "Funcionário não encontrado.";
        exit();
    }

    // Buscar os setores para exibir no formulário
    $sql_setores = "SELECT * FROM setores";
    $stmt_setores = $conn->prepare($sql_setores);
    $stmt_setores->execute();
    $result_setores = $stmt_setores->fetchAll(PDO::FETCH_ASSOC);

    // Verificar se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Coletar e sanitizar os dados do formulário
        $nome = htmlspecialchars($_POST['nome']);
        $cargo = htmlspecialchars($_POST['cargo']);
        $setor_id = $_POST['setor'];
        $telefone = htmlspecialchars($_POST['telefone']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $salario = $_POST['salario'];
        $data_admissao = $_POST['data_admissao'];

        // Atualizar os dados no banco de dados
        $sql_update = "UPDATE funcionarios SET nome = :nome, cargo = :cargo, setor_id = :setor_id, telefone = :telefone, email = :email, salario = :salario, data_admissao = :data_admissao WHERE funcionario_id = :id";
        $stmt_update = $conn->prepare($sql_update);

        $stmt_update->bindParam(':nome', $nome);
        $stmt_update->bindParam(':cargo', $cargo);
        $stmt_update->bindParam(':setor_id', $setor_id);
        $stmt_update->bindParam(':telefone', $telefone);
        $stmt_update->bindParam(':email', $email);
        $stmt_update->bindParam(':salario', $salario);
        $stmt_update->bindParam(':data_admissao', $data_admissao);
        $stmt_update->bindParam(':id', $id, PDO::PARAM_INT);

        // Executar a atualização
        if ($stmt_update->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Erro ao atualizar os dados do funcionário.";
            print_r($stmt_update->errorInfo());
        }
    }
} else {
    echo "ID não encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funcionários </title>
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

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
<div class="container">
    <h2 class="mt-5">Atualizar Funcionário</h2>
    <form action="?id=<?php echo $row['funcionario_id']; ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $row['funcionario_id']; ?>">

        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" id="nome" value="<?php echo $row['nome']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="cargo" class="form-label">Cargo</label>
            <input type="text" name="cargo" class="form-control" id="cargo" value="<?php echo $row['cargo']; ?>" required>
        </div>

        <!-- Campo de seleção para o setor -->
        <div class="mb-3">
            <label for="setor" class="form-label">Setor</label>
            <select class="form-control" id="setor_id" name="setor" required>
                <?php foreach ($result_setores as $setor) { ?>
                    <option value="<?= $setor['setor_id'] ?>" <?php if ($setor['setor_id'] == $row['setor_id']) echo 'selected'; ?>>
                        <?= $setor['nome'] ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="text" name="telefone" class="form-control" id="telefone" value="<?php echo $row['telefone']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="email" value="<?php echo $row['email']; ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="salario" class="form-label">Salário</label>
            <input type="text" name="salario" class="form-control" id="salario" value="<?php echo $row['salario']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="data_admissao" class="form-label">Data de Admissão</label>
            <input type="date" name="data_admissao" class="form-control" id="data_admissao" value="<?php echo $row['data_admissao']; ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
</div>
                </main>
</body>
</html>
