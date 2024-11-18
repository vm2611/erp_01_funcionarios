<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Verificar se o ID existe no banco antes de tentar excluir
    $sql_verificar_id = "SELECT COUNT(*) FROM funcionarios WHERE funcionario_id = :id";
    $stmt_verificar_id = $conn->prepare($sql_verificar_id);
    $stmt_verificar_id->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt_verificar_id->execute();
    $count = $stmt_verificar_id->fetchColumn();

    if ($count == 0) {
        echo "Nenhum funcionário encontrado com o ID: $id.";
        exit();
    }

    try {
        // Desabilitar temporariamente as verificações de chave estrangeira (apenas para teste)
        $conn->exec("SET foreign_key_checks = 0;");

        // Exclua os itens do pedido relacionados ao funcionário
        $sql_excluir_itens = "DELETE FROM itens_pedidos WHERE pedido_id IN (SELECT pedido_id FROM pedidos WHERE funcionario_id = :id)";
        $stmt_excluir_itens = $conn->prepare($sql_excluir_itens);
        $stmt_excluir_itens->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_excluir_itens->execute();

        if ($stmt_excluir_itens->rowCount() > 0) {
            echo "Itens de pedido excluídos com sucesso.<br>";
        } else {
            echo "Nenhum item de pedido encontrado para exclusão.<br>";
        }

        // Exclua os pedidos relacionados ao funcionário
        $sql_excluir_pedidos = "DELETE FROM pedidos WHERE funcionario_id = :id";
        $stmt_excluir_pedidos = $conn->prepare($sql_excluir_pedidos);
        $stmt_excluir_pedidos->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_excluir_pedidos->execute();

        if ($stmt_excluir_pedidos->rowCount() > 0) {
            echo "Pedidos excluídos com sucesso.<br>";
        } else {
            echo "Nenhum pedido encontrado para exclusão.<br>";
        }

        // Exclua as manutenções relacionadas ao funcionário
        $sql_excluir_manutencao = "DELETE FROM manutencoes WHERE responsavel_id = :id";
        $stmt_excluir_manutencao = $conn->prepare($sql_excluir_manutencao);
        $stmt_excluir_manutencao->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_excluir_manutencao->execute();

        if ($stmt_excluir_manutencao->rowCount() > 0) {
            echo "Manutenções excluídas com sucesso.<br>";
        } else {
            echo "Nenhuma manutenção encontrada para exclusão.<br>";
        }

        // Agora, exclua o funcionário
        $sql_excluir_funcionario = "DELETE FROM funcionarios WHERE funcionario_id = :id";
        $stmt_excluir_funcionario = $conn->prepare($sql_excluir_funcionario);
        $stmt_excluir_funcionario->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt_excluir_funcionario->execute()) {
            echo "Funcionário excluído com sucesso.<br>";
            // Redireciona de volta para a página principal (com a lista de funcionários)
            header("Location: index.php");
            exit();
        } else {
            echo "Erro ao excluir o funcionário.<br>";
        }

        // Reabilitar as verificações de chave estrangeira
        $conn->exec("SET foreign_key_checks = 1;");

    } catch (PDOException $e) {
        echo "Erro ao excluir registros: " . $e->getMessage();
    }
} else {
    echo "ID não fornecido.";
}

$conn = null;  // Fecha a conexão
?>
