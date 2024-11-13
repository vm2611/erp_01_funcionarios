<?php
include 'config.php';

// Ativar a exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar se o ID foi passado na URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Buscar os dados do funcionário
    $sql = "SELECT * FROM funcionarios WHERE funcionario_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC); // Obtém os dados do registro

    // Verificar se o funcionário foi encontrado
    if (!$row) {
        echo "Funcionário não encontrado.";
        exit(); // Adicionar exit() para evitar que o restante da página seja executado
    }

    // Verificar se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Coletar os dados do formulário e sanitizar
        $nome = htmlspecialchars($_POST['nome']);
        $cargo = htmlspecialchars($_POST['cargo']);
        $setor_id = $_POST['setor']; // Certifique-se de que o setor_id esteja correto
        $telefone = htmlspecialchars($_POST['telefone']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $salario = $_POST['salario'];
        $data_admissao = $_POST['data_admissao'];

        // Atualizar os dados no banco de dados
        $sql_update = "UPDATE funcionarios SET nome = :nome, cargo = :cargo, setor_id = :setor_id, telefone = :telefone, email = :email, salario = :salario, data_admissao = :data_admissao WHERE funcionario_id = :id";
        $stmt_update = $conn->prepare($sql_update);

        $stmt_update->bindParam(':nome', $nome);
        $stmt_update->bindParam(':cargo', $cargo);
        $stmt_update->bindParam(':setor_id', $setor_id);
        $stmt_update->bindParam(':telefone', $telefone);
        $stmt_update->bindParam(':email', $email);
        $stmt_update->bindParam(':salario', $salario);
        $stmt_update->bindParam(':data_admissao', $data_admissao);
        $stmt_update->bindParam(':id', $id, PDO::PARAM_INT);

        // Executar a atualização
        if ($stmt_update->execute()) {
            // Redirecionar para o index.php após a atualização bem-sucedida
            header("Location: index.php");
            exit(); // Certifique-se de que a execução do código pare aqui após o redirecionamento
        } else {
            echo "Erro ao atualizar os dados do funcionário.";
            print_r($stmt_update->errorInfo()); // Adicione esta linha para ver o erro
        }
    }
} else {
    echo "ID não encontrado.";
    exit(); // Adicionar exit() para evitar que o restante da página seja executado
}
