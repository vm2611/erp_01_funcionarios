<?php
include 'config.php';

$sql = "SELECT * FROM funcionarios";
$result = $conn->query($sql);

if ($result->num_rows > 0) {  // Verifica se há registros retornados
    echo '<table class="table table-bordered">';
    echo '<tr><th>ID</th><th>Nome</th><th>Cargo</th><th>Setor_id</th><th>Telefone</th><th>Email</th><th>Data_Admissao</th><th>Salario</th><th>Metodo_Pagamento</th><th>Ações</th></tr>';
    while ($row = $result->fetch_assoc()) {  // Loop através de cada registro retornado
        echo '<tr>';
        echo '<td>' . $row["id"] . '</td>';
        echo '<td>' . $row["nome"] . '</td>';
        echo '<td>' . $row["cargo"] . '</td>';
        echo '<td>' . $row["setor_id"] . '</td>';
        echo '<td>' . $row["telefone"] . '</td>';
        echo '<td>' . $row["email"] . '</td>';
        echo '<td>' . $row["data_admissao"] . '</td>';
        echo '<td>' . $row["salario"] . '</td>';
        echo '<td>' . $row["metodo_pagamento"] . '</td>';
        echo '<td>';
        echo '<a href="update.php?id=' . $row["id"] . '" class="btn btn-success">Editar</a> ';  // Link para editar
        echo '<a href="delete.php?id=' . $row["id"] . '" class="btn btn-danger">Excluir</a>';    // Link para deletar
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo "0 resultados";  // Exibe mensagem se não houver registros
}

$conn->close();  // Fecha a conexão com o banco de dados
?>
