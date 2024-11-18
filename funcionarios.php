<?php
include 'config.php';

// Exibir erros para depuração
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Coletando os dados do formulário
$nome = $_POST['nome'] ?? '';
$cargo = $_POST['cargo'] ?? '';
$setor_id = $_POST['setor'] ?? '';
$telefone = $_POST['telefone'] ?? '';
$email = $_POST['email'] ?? '';
$admissao = $_POST['admissao'] ?? '';
$salario = $_POST['salario'] ?? '';
$metodo_pagamento = $_POST['metodo_pagamento'] ?? '';

// Verificação de envio do formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Validar salário
    $salario = str_replace(',', '.', $salario);
    if (!is_numeric($salario) || $salario <= 0) {
        $salario = 0;
    }

    // Verificar se o setor é válido
    $sql_verifica_setor = "SELECT * FROM setores WHERE setor_id = :setor_id";
    $stmt = $conn->prepare($sql_verifica_setor);
    $stmt->bindParam(':setor_id', $setor_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Inserir dados diretamente na tabela de funcionários
        $sql_insercao = "INSERT INTO funcionarios (nome, cargo, setor_id, telefone, email, data_admissao, salario, metodo_pagamento)
                         VALUES (:nome, :cargo, :setor_id, :telefone, :email, :admissao, :salario, :metodo_pagamento)";
        $stmt_inserir = $conn->prepare($sql_insercao);
        $stmt_inserir->bindParam(':nome', $nome);
        $stmt_inserir->bindParam(':cargo', $cargo);
        $stmt_inserir->bindParam(':setor_id', $setor_id);
        $stmt_inserir->bindParam(':telefone', $telefone);
        $stmt_inserir->bindParam(':email', $email);
        $stmt_inserir->bindParam(':admissao', $admissao);
        $stmt_inserir->bindParam(':salario', $salario, PDO::PARAM_STR);
        $stmt_inserir->bindParam(':metodo_pagamento', $metodo_pagamento);

        if ($stmt_inserir->execute()) {
            // Redirecionar para atualizar a tabela e evitar reenvio do formulário
            header("Location: funcionarios.php");
            exit;
        } else {
            echo "<p>Erro ao cadastrar o funcionário.</p>";
        }
    } else {
        echo "<p>Setor inválido.</p>";
    }
}

// Consultas para buscar todos os setores
$sql_setores = "SELECT * FROM setores";
$stmt_setores = $conn->prepare($sql_setores);
$stmt_setores->execute();
$result_setores = $stmt_setores->fetchAll(PDO::FETCH_ASSOC);

// Consulta para buscar todos os funcionários cadastrados
$sql_funcionarios = "SELECT f.funcionario_id, f.nome, f.cargo, s.nome AS setor, f.telefone, f.email, f.data_admissao, f.salario, f.metodo_pagamento
FROM funcionarios f
JOIN setores s ON f.setor_id = s.setor_id";
$stmt_funcionarios = $conn->prepare($sql_funcionarios);
$stmt_funcionarios->execute();
$result_funcionarios = $stmt_funcionarios->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <!--NÃO ALTERAR-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Funcionários</title>
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
    background-color: var(--bg-purple); /* Cabeçalho roxo */
    color: var(--text-white);
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
            <!--NÃO ALTERAR-->
            
            <main role="main" class="container">
                <div class="container">
                    <h1 class="h2">CADASTRO DE FUNCIONARIOS</h1>
                </div>
                <!--NÃO ALTERAR-->
                <!--MEXER AQUI -->
                <div class="conteiner"></div>
                <form method="POST" action="funcionarios.php">
                <div class="form-group">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>
                <div class="conteiner"></div>
                <form class="" method="" action="">
                    <div class="mb-3">
                        <label for="cargo" class="form-label">Cargo</label>
                        <input type="text" class="form-control" id="cargo" name="cargo" required>
                    </div>
                    <div class="mb-3">
                        <label for="setor" class="form-label">Setor</label>
                        <select class="form-control" id="setor" name="setor" required>
                            <?php foreach ($result_setores as $setor) { ?>
                                <option value="<?= $setor['setor_id'] ?>"><?= $setor['nome'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
    <label for="telefone" class="form-label">Telefone</label>
    <input type="text" class="form-control phone" id="telefone" name="telefone" required>
</div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="admissao" class="form-label">Data de Admissão</label>
                        <input type="date" class="form-control" id="admissao" name="admissao" required>
                    </div>
                    <div class="mb-3">
    <label for="salario" class="form-label">Salário</label>
    <input type="text" class="form-control salary" id="salario" name="salario" required>
</div>
                    <div class="mb-3">
                        
                        <label for="metodo_pagamento" class="form-label">Método de Pagamento</label>
                        <input class="text" id="metodo_pagamento" name="metodo_pagamento" required>
                           
                    
                    </div>
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                </form>
                


              
<h2 class="mt-5">Funcionários Cadastrados</h2>
<table class="table table-bordered">
   <thead>
       <tr>
           <th>Nome</th>
           <th>Cargo</th>
           <th>Setor</th>
           <th>Telefone</th>
           <th>Email</th>
           <th>Data de Admissão</th>
           <th>Salário</th>
           <th>Método de Pagamento</th>
           <th>Ações</th>
       </tr>
   </thead>
   <tbody>
       <?php foreach ($result_funcionarios as $funcionario) { ?>
           <tr>
               <td><?= htmlspecialchars($funcionario['nome']) ?></td>
               <td><?= htmlspecialchars($funcionario['cargo']) ?></td>
               <td><?= htmlspecialchars($funcionario['setor']) ?></td>
               <td><?= htmlspecialchars($funcionario['telefone']) ?></td>
               <td><?= htmlspecialchars($funcionario['email']) ?></td>
               <td><?= htmlspecialchars($funcionario['data_admissao']) ?></td>
               <td><?= htmlspecialchars(number_format($funcionario['salario'], 2, ',', '.')) ?></td>
               <td><?= htmlspecialchars($funcionario['metodo_pagamento']) ?></td>
               <td>
                   <a href="update_funcionarios.php?id=<?= $funcionario['funcionario_id'] ?>" class="btn btn-warning btn-sm">Alterar</a>
                   <a href="delete_funcionarios.php?id=<?= $funcionario['funcionario_id'] ?>" class="btn btn-danger btn-sm">Excluir</a>
               </td>
           </tr>
       <?php } ?>
       <?php if (empty($result_funcionarios)) { ?>
           <tr>
               <td colspan="9">Nenhum funcionário cadastrado.</td>
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
