<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $cargo = $_POST["cargo"];
    $setor_id = $_POST["setor"];
    $telefone = $_POST["telefone"];
    $email = $_POST["email"];
    $data_admissao = $_POST["admissao"];
    $salario = $_POST["salary"];
    $metodo_pagamento = $_POST["metodo_pagamento"];

    // SQL para inserir um novo funcionário
    $sql = "INSERT INTO funcionarios (nome, cargo, setor_id, telefone, email, data_admissao, salario, metodo_pagamento_id) 
            VALUES (:nome, :cargo, :setor_id, :telefone, :email, :data_admissao, :salario, :metodo_pagamento)";

    $stmt = $conn->prepare($sql);

    // Associando os valores aos parâmetros
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cargo', $cargo);
    $stmt->bindParam(':setor_id', $setor_id);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':data_admissao', $data_admissao);
    $stmt->bindParam(':salario', $salario);
    $stmt->bindParam(':metodo_pagamento', $metodo_pagamento);

    // Executando a consulta
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Erro ao inserir dados: " . $stmt->errorInfo()[2];
    }
}

$conn = null;
?>
