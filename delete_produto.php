<?php
include 'config.php';  // Inclui a configuração do banco de dados

if (isset($_GET['id'])) {  // Verifica se o ID foi passado como parâmetro
    $id = $_GET['id'];

    // Verifique se a variável de conexão está corretamente configurada no config.php
    if (isset($conn)) {  // Usando a variável $conn
        // Desabilita as verificações de chave estrangeira
        $conn->exec("SET foreign_key_checks = 0");

        // Exclui os itens de pedido, estoque e o produto
        $sql_itens_pedidos = "DELETE FROM itens_pedidos WHERE produto_id = :id";
        $stmt_itens_pedidos = $conn->prepare($sql_itens_pedidos);
        $stmt_itens_pedidos->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_itens_pedidos->execute();  // Executa a exclusão na tabela itens_pedidos

        $sql_estoque = "DELETE FROM estoque WHERE produto_id = :id";
        $stmt_estoque = $conn->prepare($sql_estoque);
        $stmt_estoque->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_estoque->execute();  // Executa a exclusão na tabela estoque

        $sql_produto = "DELETE FROM produtos WHERE produto_id = :id";  // Usando bind para evitar SQL Injection
        $stmt_produto = $conn->prepare($sql_produto);
        $stmt_produto->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt_produto->execute()) {
            // Habilita novamente as verificações de chave estrangeira
            $conn->exec("SET foreign_key_checks = 1");
            header("Location: produtos.php");  // Redireciona para a página de produtos
            exit();
        } else {
            // Reabilita as verificações de chave estrangeira em caso de erro
            $conn->exec("SET foreign_key_checks = 1");
            echo "Erro ao deletar o produto.";
        }
    } else {
        echo "Erro na conexão com o banco de dados.";
    }
} else {
    echo "ID não fornecido.";
}
?>
