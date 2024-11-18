<?php
$host = 'localhost';
$user = 'root';
$password = ''; // Substitua pela senha do MySQL, se houver
$dbname = 'empresa'; // Nome do banco de dados

try {
    // Conexão com o servidor MySQL (sem especificar o banco de dados ainda)
    $conn = new PDO("mysql:host=$host", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar se o banco de dados já existe
    $dbExists = $conn->query("SHOW DATABASES LIKE '$dbname'")->rowCount() > 0;

    if (!$dbExists) {
        // Criar o banco de dados se ele não existir
        $conn->exec("CREATE DATABASE $dbname");
        echo "Banco de dados criado com sucesso!<br>";
    }

    // Conectar ao banco de dados específico
    $conn->exec("USE $dbname");

    // Verificar se a tabela "fornecedores" já existe
    $tableExists = $conn->query("SHOW TABLES LIKE 'fornecedores'")->rowCount() > 0;
    if (!$tableExists) {
        // Carregar e executar o script SQL para criar tabelas se elas não existirem
        $databaseFile = 'database.sql';
        $sql = file_get_contents($databaseFile);
        if ($sql === false) {
            throw new Exception("Não foi possível ler o arquivo SQL");
        }
        $conn->exec($sql);
        echo "Tabelas criadas ou atualizadas com sucesso!<br>";
    }

} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
    exit();
}
?>
