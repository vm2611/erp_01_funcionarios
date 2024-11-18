<?php
include 'config.php';

// Estabelecer a conexão com o banco de dados
$pdo = new PDO('mysql:host=localhost;dbname=empresa', 'root', '');

// Preparar a consulta SQL para selecionar os produtos
$query = 'SELECT produto_id, nome FROM produtos';
$stmt = $pdo->query($query);

// Executar a consulta e armazenar os resultados
$result_produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Processar o formulário de movimentação de estoque
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coletar dados do formulário
    $produto_id = $_POST['produto_id'];
    $tipo_movimentacao = $_POST['tipo_movimentacao'];
    $quantidade = $_POST['quantidade'];
    $data_movimentacao = $_POST['data_movimentacao'];

    // Inserir a movimentação no banco de dados
    $sql_insert = "INSERT INTO estoque (produto_id, tipo_movimentacao, quantidade, data_movimentacao) 
                   VALUES (:produto_id, :tipo_movimentacao, :quantidade, :data_movimentacao)";
    $stmt_insert = $pdo->prepare($sql_insert);
    $stmt_insert->bindParam(':produto_id', $produto_id);
    $stmt_insert->bindParam(':tipo_movimentacao', $tipo_movimentacao);
    $stmt_insert->bindParam(':quantidade', $quantidade);
    $stmt_insert->bindParam(':data_movimentacao', $data_movimentacao);

    if ($stmt_insert->execute()) {
        echo "Movimentação registrada com sucesso!";
    } else {
        echo "Erro ao registrar movimentação!";
    }
}

// Consulta para buscar todas as movimentações de estoque
$sql_estoque = "SELECT e.estoque_id, p.nome AS produto, e.tipo_movimentacao, e.quantidade, e.data_movimentacao
                FROM estoque e
                JOIN produtos p ON e.produto_id = p.produto_id";
$stmt_estoque = $pdo->prepare($sql_estoque);
$stmt_estoque->execute();
$result_estoque = $stmt_estoque->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <!--NÃO ALTERAR-->
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
                    <h1 class="h2">ESTOQUE</h1>
                </div>

                <form method="POST" action="estoque.php">
                    <div class="form-group">
                        <label for="produto_id">Produto</label>
                        <select class="form-control" id="produto_id" name="produto_id" required>
                            <?php foreach ($result_produtos as $produto) { ?>
                                <option value="<?= $produto['produto_id'] ?>"><?= $produto['nome'] ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tipo_movimentacao">Tipo de Movimentação</label>
                        <select class="form-control" id="tipo_movimentacao" name="tipo_movimentacao" required>
                            <option value="entrada">Entrada</option>
                            <option value="saida">Saída</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="quantidade">Quantidade</label>
                        <input type="number" id="quantidade" name="quantidade" required>
                    </div>

                    <div class="form-group">
                        <label for="data_movimentacao">Data de Movimentação</label>
                        <input type="date" id="data_movimentacao" name="data_movimentacao" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Registrar Movimentação</button>
                </form>


        <!-- Tabela de movimentações de estoque -->
        <h2 class="mt-5">Movimentações de Estoque</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Tipo de Movimentação</th>
                    <th>Quantidade</th>
                    <th>Data da Movimentação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result_estoque as $movimentacao) { ?>
                    <tr>
                        <td><?= htmlspecialchars($movimentacao['produto']) ?></td>
                        <td><?= htmlspecialchars($movimentacao['tipo_movimentacao']) ?></td>
                        <td><?= htmlspecialchars($movimentacao['quantidade']) ?></td>
                        <td><?= htmlspecialchars($movimentacao['data_movimentacao']) ?></td>
                        <td>
                        <a href="update_estoque.php?id=<?= $movimentacao['estoque_id'] ?>" class="btn btn-warning btn-sm">Alterar</a>
<a href="delete_estoque.php?id=<?= $movimentacao['estoque_id'] ?>" class="btn btn-danger btn-sm">Excluir</a>

</tr>
       <?php } ?>
       <?php if (empty($result_estoque)) { ?>
           <tr>
               <td colspan="9">Nenhum estoque cadastrado.</td>
           </tr>
       <?php } ?>
   </tbody>
</table>

            </main>
        </div>
    </div>
    <!--NÃO ALTERAR-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script type='text/javascript' src='//code.jquery.com/jquery-compat-git.js'></script>
    <script type='text/javascript' src='//igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js'></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script>
<script src="script.js"></script>


</html>
