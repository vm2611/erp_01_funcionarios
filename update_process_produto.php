<?php
include 'config.php';

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura os dados do formulário
    $id = intval($_POST['id']);
    $nome = htmlspecialchars($_POST['nome']);
    $email = htmlspecialchars($_POST['sobrenome']);  // 'sobrenome' parece estar sendo usado para email
    $telefone = htmlspecialchars($_POST['telefone']);

    try {
        // Prepara a consulta de atualização
        $stmt = $pdo->prepare("
            UPDATE usuarios 
            SET nome = ?, email = ?, telefone = ? 
            WHERE id = ?
        ");

        // Executa a consulta com os dados capturados
        if ($stmt->execute([$nome, $email, $telefone, $id])) {
            // Redireciona ou exibe uma mensagem de sucesso
            header("Location: success.php"); // Redireciona para uma página de sucesso
            exit;
        } else {
            throw new Exception('Falha ao atualizar os dados.');
        }
    } catch (Exception $e) {
        // Em caso de erro, exibe a mensagem de erro
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}
?>
