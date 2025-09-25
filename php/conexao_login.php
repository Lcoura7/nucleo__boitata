<?php
session_start(); // IMPORTANTE: Iniciar a sessão

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'projtec';

$conn = new mysqli($host, $user, $pass, $db); 

if($conn->connect_error){
    die('Connection failed'. $conn->connect_error);
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password']; 
        
        // Use prepared statement para segurança
        $query = "SELECT `username`, `password` FROM usuarios WHERE `username` = ? AND `password` = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 0){
            // Login falhou
            header("location: ../Login.php?status=False&message=Usuario ou senha incorretos");
            exit();
        } else {
            // Login bem-sucedido - DEFINIR A SESSÃO ANTES DO REDIRECIONAMENTO
            $_SESSION['logado'] = true;
            $_SESSION['username'] = $username; // Opcional: salvar o username
            header("location: ../mapa.php");
            exit();
        }
    } else {
        header("location: ../Login.php?status=False&message=Dados incompletos");
        exit();
    }
} else {
    // Se não for POST, redirecionar para login
    header("location: ../Login.php");
    exit();
}
?>