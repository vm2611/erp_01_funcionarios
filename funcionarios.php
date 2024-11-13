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

    // Verificar se o setor e o método de pagamento são válidos
    $sql_verifica_setor = "SELECT * FROM setores WHERE setor_id = :setor_id";
    $stmt = $conn->prepare($sql_verifica_setor);
    $stmt->bindParam(':setor_id', $setor_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $sql_verifica_metodo = "SELECT * FROM metodo_pagamento WHERE metodo_pagamento_id = :metodo_pagamento";
        $stmt_metodo = $conn->prepare($sql_verifica_metodo);
        $stmt_metodo->bindParam(':metodo_pagamento', $metodo_pagamento);
        $stmt_metodo->execute();

        if ($stmt_metodo->rowCount() > 0) {
            // Inserir dados
            $sql_insercao = "INSERT INTO funcionarios (nome, cargo, setor_id, telefone, email, data_admissao, salario, metodo_pagamento_id)
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
            echo "<p>Método de pagamento inválido.</p>";
        }
    } else {
        echo "<p>Setor inválido.</p>";
    }
}

// Consultas para buscar todos os setores e métodos de pagamento
$sql_setores = "SELECT * FROM setores";
$stmt_setores = $conn->prepare($sql_setores);
$stmt_setores->execute();
$result_setores = $stmt_setores->fetchAll(PDO::FETCH_ASSOC);

$sql_metodo_pagamento = "SELECT * FROM metodo_pagamento";
$stmt_metodo_pagamento = $conn->prepare($sql_metodo_pagamento);
$stmt_metodo_pagamento->execute();
$result_metodo_pagamento = $stmt_metodo_pagamento->fetchAll(PDO::FETCH_ASSOC);

// Consulta para buscar todos os funcionários cadastrados
$sql_funcionarios = "SELECT f.funcionario_id, f.nome, f.cargo, s.nome AS setor, f.telefone, f.email, f.data_admissao, f.salario, mp.nome AS metodo_pagamento
FROM funcionarios f
JOIN setores s ON f.setor_id = s.setor_id
JOIN metodo_pagamento mp ON f.metodo_pagamento_id = mp.metodo_pagamento_id";
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
    
</head>

<body>
    <!--NÃO ALTERAR-->
    <div class="container-fluid">
        <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-purple sidebar">
                <div class="logo">
                    <h2>HORIZON+</h2>
                </div>
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">
                                <i class="bi bi-phone-fill icon-large"></i>
                                <span>Produtos</span>
                                <i id="indicator" class="bi bi-caret-left-fill indicator"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-basket2-fill icon-large"></i>
                                <span>Pedidos</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-people-fill icon-large"></i>
                                <span>Fornecedores</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="funcionarios.php">
                                <i class="bi bi-person-badge-fill icon-large"></i>
                                <span>Funcionários</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-box-fill icon-large"></i>
                                <span>Estoque</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-pc-display-horizontal icon-large"></i>
                                <span>Equipamentos</span>
                            </a>
                        </li>
                    </ul>
                    <div class="user">
                        <div class="nav-item">
                            <div class="d-flex align-items-center card-user">
                                <i class="bi bi-person-circle users"></i>
                                <div class="ml-2">
                                    <div>Funcionário</div>
                                    <div class="text-muted" style="font-size: 0.8em;">Função xyz</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <!--NÃO ALTERAR-->
            
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                    <h1 class="h2">CADASTRO DE FUNCIONARIOS</h1>
                </div>
                <!--NÃO ALTERAR-->
                <!--MEXER AQUI -->
                <div class="conteiner"></div>
                <form method="POST" action="funcionarios.php">
                <div class="mb-3">
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
                        <select class="form-control" id="metodo_pagamento" name="metodo_pagamento" required>
                            <?php foreach ($result_metodo_pagamento as $metodo) { ?>
                                <option value="<?= $metodo['metodo_pagamento_id'] ?>"><?= $metodo['nome'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                </form>
                


              
<h2 class="mt-5">Funcionários Cadastrados</h2>
<table class="table table-striped">
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
                   <a href="update.php?id=<?= $funcionario['funcionario_id'] ?>" class="btn btn-warning btn-sm">Alterar</a>
                   <a href="delete.php?id=<?= $funcionario['funcionario_id'] ?>" class="btn btn-danger btn-sm">Excluir</a>
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
