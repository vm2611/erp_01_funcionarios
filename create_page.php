<?php
include 'config.php';

// Ativa a exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Função para buscar funcionários
function fetchFuncionarios($conn) {
    $sql = "SELECT funcionario_id, nome FROM funcionarios";
    $stmt = $conn->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Busca funcionários
$funcionarios = fetchFuncionarios($conn);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Valida se os campos obrigatórios estão preenchidos
        if (!isset($_POST['nome_equip'], $_POST['problema'], $_POST['data_inicio'], $_POST['status'], $_POST['funcionario_id'])) {
            throw new Exception("Todos os campos obrigatórios devem ser preenchidos.");
        }

        // Inserir dados no banco
        $sql = "INSERT INTO manutencoes (equipamento, descricao_problema, data_inicio, data_termino, status, funcionario_id)
                VALUES (:equipamento, :descricao_problema, :data_inicio, :data_termino, :status, :funcionario_id)";
        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ':equipamento' => $_POST['nome_equip'],
            ':descricao_problema' => $_POST['problema'],
            ':data_inicio' => $_POST['data_inicio'],
            ':data_termino' => $_POST['data_termino'] ?: null,
            ':status' => $_POST['status'],
            ':funcionario_id' => $_POST['funcionario_id']
        ]);

        // Redireciona para outra página
        header("Location: exibir_manutencoes.php");
        exit();
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <!--NÃO ALTERAR-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="src/css/styles.css">
</head>
<style>:root {
    --bg-purple: #4A148C;
    --depp-purple: #3c096c;
    --text-white: #fff;
    --font-size-logo: 1.5rem;
    --font-weight-bold: bold;
    --font-size-base: 0.875rem;
    --padding-top-sidebar: 0; /* Alinhamento ao topo */
    --margin-bottom-logo: 20px;
    --icon-size-small: 16px;
    --icon-size-medium: 24px;
    --icon-size-large: 32px;
}

body {
    font-size: var(--font-size-base);
}

.sidebar {
    background-color: var(--bg-purple);
    height: auto;
    padding-top: var(--padding-top-sidebar);
}

.sidebar .logo {
    position: relative;
    text-align: center;
    color: var(--text-white);
    font-size: var(--font-size-logo);
    font-weight: bolder;
    padding: 15px;
    margin: 0;
    margin-left: -15px;
    width: 100%; /* Largura relativa */
    max-width: 100%; /* Garantir que a logo não exceda a largura do contêiner */
    background-color: var(--deep-purple); /* Corrigi a cor: deep-purple em vez de depp-purple */
}

.sidebar .nav-link {
    color: var(--text-white);
    font-weight: 500;
    display: flex;
    align-items: center;
    margin-top: 15px;
}

.sidebar .nav-link.active {
    color: var(--text-white);
}

/* Estilo para o indicador (seta) */
.sidebar .nav-link.active .indicator {
    margin-left: auto;
}


.sidebar .nav-link:not(.active):hover {
    background-color: var(--depp-purple);
    border-radius: 15px;
}

.sidebar .nav-link span {
    margin-left: 10px;
}

.sidebar-sticky {
    position: -webkit-sticky;
    position: sticky;
    top: 0;
    height: calc(100vh - 48px);
    padding-top: 0.5rem;
    overflow-x: hidden;
    overflow-y: auto;
}

.form-group label {
    font-weight: var(--font-weight-bold);
}

/* Personalização de tamanho dos ícones */
.icon-small {
    font-size: var(--icon-size-small);
}

.icon-medium {
    font-size: var(--icon-size-medium);
}

.icon-large {
    font-size: var(--icon-size-large);
}

.user{
    list-style-type: none;
    margin-top: 40px;
    margin-left: 15px;
    color: var(--text-white);
}
.card-user{
    background-color: var(--depp-purple);
    padding-left: 10px;
    width: 140px;
    padding-top: 4px;
    padding-bottom: 4px;
    border-radius: 15px;
}

.users{
    font-size: var(--icon-size-large);
}

.flex{
    display: flex;
    gap: 17px;
}

.flex .form-group select,
.flex .form-group input{
    width: 600px;
}
.btn-primary {
    margin-top: 2%;
    margin-left: 0%;
    width: 100%;
    background-color: var(--bg-purple);
    border-color: var(--depp-purple);
    color: var(--text-white); /* Garante que o texto no botão seja branco */
    display: block; /* Força o botão a ser um bloco, ocupando toda a largura disponível */
}

.btn-primary:hover {
    background-color: var(--depp-purple);
    border-color: var(--bg-purple);
}


.btn-danger{
    margin-top: 2%;
    width: 100%;
}

.main-form{
    margin-top: 5%;
    margin-left: 0%;
}

label{
    font-size: 20px;
}

.main-title{
    margin-bottom: 4%;
}

.card{
    margin-bottom: 4%;
}

.btn-create{
    margin-bottom: 1%;
}

@media (max-width: 1200px) {
    .sidebar .logo {
        font-size: calc(var(--font-size-logo) * 0.9); /* Ajuste o tamanho da fonte */
        padding: 12px;
    }
}

@media (max-width: 992px) {
    .sidebar .logo {
        font-size: calc(var(--font-size-logo) * 0.8); /* Ajuste o tamanho da fonte */
        padding: 10px;
    }
}

@media (max-width: 768px) {
    .sidebar .logo {
        font-size: calc(var(--font-size-logo) * 0.7); /* Ajuste o tamanho da fonte */
        padding: 8px;
    }
}

@media (max-width: 576px) {
    .sidebar .logo {
        font-size: calc(var(--font-size-logo) * 0.6); /* Ajuste o tamanho da fonte */
        padding: 6px;
    }

}
</style>
<body>
    <!--NÃO ALTERAR-->
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
            <!--NÃO ALTERAR-->

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 main-form">
                            <form action="create_page.php" method="POST">
                            <h1 class="h2 main-title">Manutenção de Equipamentos</h1>
                                    <div class="mb-3">
                                        <label for="nome_equip" class="form-label">Nome do
                                            Equipamento:</label>
                                        <input type="text" class="form-control" id="nome_equip" name="nome_equip"
                                            placeholder="" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="problema" class="form-label">Descrição do
                                            problema:</label>
                                        <textarea class="form-control" id="problema" name="problema"
                                            rows="3"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="data_inicio" class="form-label">Data de Inicio:</label>
                                        <input type="datetime-local" class="form-control" id="data_inicio" name="data_inicio"
                                            placeholder="" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="data_termino" class="form-label">Data de
                                            Termino:</label>
                                        <input type="datetime-local" class="form-control" id="data_termino" name="data_termino"
                                            placeholder="">
                                    </div>
                                    <label for="funcionario_responsavel" class="form-label">Funcionário Responsável</label>
<select class="form-select" id="funcionario_responsavel" name="funcionario_id" required>
    <option value="" disabled selected>Selecione um funcionário</option>
    <?php foreach ($funcionarios as $funcionario): ?>
        <option value="<?= $funcionario['funcionario_id'] ?>">
            <?= htmlspecialchars($funcionario['nome'], ENT_QUOTES, 'UTF-8') ?>
        </option>
    <?php endforeach; ?>
</select>





                                    <select class="form-select" aria-label="Default select example" id="status" name="status" required>
                                        <option selected>Status</option>
                                        <option value="quebrado">Quebrado</option>
                                        <option value="funcional">Funcional</option>
                                    </select>
                                    <button type="submit" class="btn btn-lg btn-primary send-btn">adicionar a manuntenção</button>


                                </form>
                            </div>
                        </div>

                    </div>
                </div>
                <!--NÃO ALTERAR-->

                <!--NÃO ALTERAR-->
            </main>
        </div>
    </div>
    <!--NÃO ALTERAR-->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
    <script src="src/js/script.js"></script>
    <!--NÃO ALTERAR-->
</body>

</html>