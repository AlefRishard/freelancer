<?php
$host = 'localhost';
$db = 'freelancer';
$user = 'root';
$pass = '123456'; // sua senha, se houver

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Erro de conexão: ' . $conn->connect_error);
}

$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
$endereco = $_POST['endereco'];
$telefone = $_POST['telefone'];

$sql = "INSERT INTO clientes (nome, email, senha, endereco, telefone) 
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $nome, $email, $senha, $endereco, $telefone);

if ($stmt->execute()) {
    echo "Cadastro realizado com sucesso!";
    // redirecionar se quiser:
    // header("Location: login.html");
} else {
    echo "Erro: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
