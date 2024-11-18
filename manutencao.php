<?php
include 'config.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <!--NÃO ALTERAR-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manutenção</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="src/css/styles.css">
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
}</style>
</head>

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
                            <div class="col btn-create">
                                <a href="create_page.php" class="btn btn-primary btn-create">adicionar a manutenção </a>
                            </div>
                        </div>
                        <div class="row">
                        <?php
$result = $conn->query("SELECT * FROM manutencoes");
if ($result->rowCount() > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<div class='col-3'>";
        echo "<div class='card'>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>{$row['equipamento']}</h5>";
        echo "<p class='card-text'>{$row['descricao_problema']}</p>";
        echo "<h6 class='card-subtitle mb-2 text-body-secondary'>Data Inicio: {$row['data_inicio']}</h6>";
        echo "<h6 class='card-subtitle mb-2 text-body-secondary'>Data Termino: {$row['data_termino']}</h6>";
        echo "<h6 class='card-subtitle mb-2 text-body-secondary'>Técnico Responsável: {$row['tecnico_responsavel']}</h6>";
        echo "<h6 class='card-subtitle mb-2 text-body-secondary'>Status: {$row['status']}</h6>";
        echo "<a href='editar.php?id={$row['manutencao_id']}' class='btn btn-primary btn-sm'>Editar</a>";
        echo "<a href='deletar.php?id={$row['manutencao_id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Tem certeza que deseja excluir este item?\")'>Excluir</a>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<p class='text-center'>Nenhuma manutenção cadastrada.</p>";
}
?>

                        </div>
                    </div>
                </div>
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
