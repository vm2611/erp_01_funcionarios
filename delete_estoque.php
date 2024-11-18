<?php
// Incluindo o arquivo de configuração para conectar ao banco de dados
include 'config.php';  // Certifique-se de que o arquivo config.php tem a variável $conn definida corretamente

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Excluir a movimentação de estoque com base no estoque_id
        $sql_excluir_estoque = "DELETE FROM estoque WHERE estoque_id = :id";
        $stmt_excluir_estoque = $conn->prepare($sql_excluir_estoque);  // Usando a variável $conn do config.php
        $stmt_excluir_estoque->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt_excluir_estoque->execute()) {
            echo "Movimentação de estoque excluída com sucesso.";
            header("Location: estoque.php");  // Redireciona de volta para a página de estoque
            exit();
        } else {
            echo "Erro ao excluir a movimentação de estoque.";
        }
    } catch (PDOException $e) {
        echo "Erro ao excluir: " . $e->getMessage();
    }
} else {
    echo "ID não fornecido.";
}

$conn = null;  // Fecha a conexão
?>
