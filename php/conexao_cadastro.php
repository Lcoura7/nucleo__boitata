<?php
// Configurações de conexão com o banco de dados
$host = 'localhost';
$user = 'root';  // Altere conforme necessário
$pass = '';      // Altere conforme necessário
$db = 'projtec';

// Conectar ao banco de dados
$conn = new mysqli($host, $user, $pass, $db);

// Verificar a conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verificar se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar e sanitizar os dados de entrada
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST['password']; // A senha será hasheada

    if (empty($username) || empty($password)) {
        die("Por favor, preencha todos os campos.");
    }

    // Preparar a consulta com prepared statements para evitar SQL injection
    $stmt = $conn->prepare("INSERT INTO usuarios (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        header("Location: ../Login.php?status=success");
        exit();
    } else {
        header("Location: ../Login.php?status=error&message=" . urlencode($conn->error));
        exit();
    }

}




$conn->close();
?>