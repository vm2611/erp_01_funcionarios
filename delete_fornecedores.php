<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Preparar a consulta SELECT para verificar se o fornecedor existe
    $sql = "SELECT * FROM fornecedores WHERE fornecedor_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Excluir produtos relacionados ao fornecedor
        $delete_products_sql = "DELETE FROM produtos WHERE fornecedor_id = :id";
        $delete_products_stmt = $conn->prepare($delete_products_sql);
        $delete_products_stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $delete_products_stmt->execute();

        // Agora, excluir o fornecedor
        $delete_sql = "DELETE FROM fornecedores WHERE fornecedor_id = :id";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($delete_stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            echo "Erro ao excluir fornecedor.";
        }
    } else {
        echo "Fornecedor não encontrado!";
    }
} else {
    echo "ID não fornecido!";
}

$conn = null;
?>
