<?php
include 'config.php';

// Inserir novo funcionário
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nome"])) {
    $nome = $_POST["nome"];
    $cargo = $_POST["cargo"];
    $setor_id = $_POST["setor"];
    $telefone = $_POST["telefone"];
    $email = $_POST["email"];
    $data_admissao = $_POST["admissao"];
    $salario = $_POST["salary"];
    $metodo_pagamento = $_POST["metodo_pagamento"];

    // SQL para inserir um novo funcionário
    $sql = "INSERT INTO funcionarios (nome, cargo, setor_id, telefone, email, data_admissao, salario, metodo_pagamento) 
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

// Inserir novo pedido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["data_pedido"])) {
    $data_pedido = $_POST["data_pedido"];
    $status = $_POST["status"];
    $valor_total = $_POST["valor_total"];
    $funcionario_id = $_POST["funcionario_id"];

    // SQL para inserir um novo pedido
    $sql = "INSERT INTO pedidos (data_pedido, status, valor_total, funcionario_id) 
            VALUES (:data_pedido, :status, :valor_total, :funcionario_id)";

    $stmt = $conn->prepare($sql);

    // Associando os valores aos parâmetros
    $stmt->bindParam(':data_pedido', $data_pedido);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':valor_total', $valor_total);
    $stmt->bindParam(':funcionario_id', $funcionario_id);

    // Executando a consulta
    if ($stmt->execute()) {
        header("Location: pedidos.php");
        exit();
    } else {
        echo "Erro ao inserir pedido: " . $stmt->errorInfo()[2];
    }
}

// Inserir movimentação no estoque
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["produto_id"])) {
    $produto_id = $_POST["produto_id"];
    $tipo_movimentacao = $_POST["tipo_movimentacao"];
    $quantidade = $_POST["quantidade"];
    $data_movimentacao = $_POST["data_movimentacao"];

    // SQL para inserir a movimentação no estoque
    $sql = "INSERT INTO estoque (produto_id, tipo_movimentacao, quantidade, data_movimentacao) 
            VALUES (:produto_id, :tipo_movimentacao, :quantidade, :data_movimentacao)";

    $stmt = $conn->prepare($sql);

    // Associando os valores aos parâmetros
    $stmt->bindParam(':produto_id', $produto_id);
    $stmt->bindParam(':tipo_movimentacao', $tipo_movimentacao);
    $stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
    $stmt->bindParam(':data_movimentacao', $data_movimentacao);

    // Executando a consulta
    if ($stmt->execute()) {
        header("Location: estoque.php");
        exit();
    } else {
        echo "Erro ao inserir dados: " . $stmt->errorInfo()[2];
    }
}

// Inserir manutenção
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['nome_equip'])) {
    try {
        // Valida se os campos obrigatórios estão preenchidos
        if (!isset($_POST['nome_equip'], $_POST['problema'], $_POST['data_inicio'], $_POST['status'], $_POST['funcionario_id'])) {
            throw new Exception("Todos os campos obrigatórios devem ser preenchidos.");
        }

        // Inserir dados no banco
        $sql = "INSERT INTO manutencoes (equipamento, descricao_problema, data_inicio, data_termino, tecnico_responsavel, status, responsavel_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            $_POST['nome_equip'],
            $_POST['problema'],
            $_POST['data_inicio'],
            $_POST['data_termino'] ?: null,
            $_POST['tecnico_responsavel'],  // Assumindo que o nome do técnico também seja passado
            $_POST['status'],
            $_POST['funcionario_id']
        ]);

        // Verifique se a inserção foi bem-sucedida
        if ($stmt->rowCount() > 0) {
            echo "Manutenção registrada com sucesso.";
            header("Location: exibir_manutencoes.php");
            exit();
        } else {
            echo "Erro ao registrar a manutenção.";
        }
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }
}

// Fechar a conexão
$conn = null;
?>
