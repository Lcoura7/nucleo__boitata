<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'projtec';

$conn = new mysqli($host, $user, $pass, $db); 

if($conn->connect_error){
    die('Connection failed'. $conn->connect_error);
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset( $_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password']; 
        $query ="SELECT `username`, `password` FROM usuarios WHERE `username` = ? and `password` = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 0){
            header("location: ../Login.php?status=False");
            exit();
        }else{
            header("location: ../Mapa.html?status=True");
            exit();
        }
    }else{
        header("location: ../Login.php?status=False");
        exit();
    }
}
?>