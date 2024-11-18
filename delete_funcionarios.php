<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Exclua os itens do pedido relacionados ao funcionário
    $sql_excluir_itens = "DELETE FROM itens_pedidos WHERE pedido_id IN (SELECT pedido_id FROM pedidos WHERE funcionario_id = :id)";
    $stmt_excluir_itens = $conn->prepare($sql_excluir_itens);
    $stmt_excluir_itens->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt_excluir_itens->execute();

    // Exclua os pedidos relacionados ao funcionário
    $sql_excluir_pedidos = "DELETE FROM pedidos WHERE funcionario_id = :id";
    $stmt_excluir_pedidos = $conn->prepare($sql_excluir_pedidos);
    $stmt_excluir_pedidos->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt_excluir_pedidos->execute();

    // Exclua as manutenções relacionadas ao funcionário
    $sql_excluir_manutencao = "DELETE FROM manutencoes WHERE responsavel_id = :id";
    $stmt_excluir_manutencao = $conn->prepare($sql_excluir_manutencao);
    $stmt_excluir_manutencao->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt_excluir_manutencao->execute();

    // Agora, exclua o funcionário
    $sql_excluir_funcionario = "DELETE FROM funcionarios WHERE funcionario_id = :id";
    $stmt_excluir_funcionario = $conn->prepare($sql_excluir_funcionario);
    $stmt_excluir_funcionario->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt_excluir_funcionario->execute()) {
        echo "Funcionário excluído com sucesso.";
        header("Location: index.php");  // Redireciona de volta para a página principal (com a lista de funcionários)
        exit();
    } else {
        echo "Erro ao excluir o funcionário.";
    }
} else {
    echo "ID não fornecido.";
}

$conn = null;  // Fecha a conexão
?>
