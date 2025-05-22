<?php

// Iniciar a sessão para poder usar variáveis de sessão (ex: para guardar o usuário logado)
session_start();

// --- 1. Configurações do Banco de Dados ---
// ATENÇÃO: Substitua esses valores pelos seus dados reais do banco de dados!
$servername = "localhost"; // Geralmente 'localhost' para desenvolvimento local
$username = "root"; // Seu usuário do banco de dados
$password = "123456"; // Sua senha do banco de dados
$dbname = "freelancer"; // O nome do seu banco de dados

// --- 2. Conexão com o Banco de Dados ---
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// --- 3. Processamento do Formulário de Login ---
// Verifica se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pega os dados do formulário e evita injeção de SQL (sanitização básica)
    $username_input = $conn->real_escape_string($_POST['username']);
    $password_input = $conn->real_escape_string($_POST['password']);

    // Prepara a consulta SQL para buscar o usuário
    // IMPORTANTE: Em um sistema real, NUNCA armazene senhas em texto puro!
    // Use funções de hash como password_hash() para armazenar e password_verify() para verificar.
    $sql = "SELECT id, username, password_hash FROM usuarios WHERE username = '$username_input'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Usuário encontrado, agora verifica a senha
        $row = $result->fetch_assoc();
        $stored_password_hash = $row['password_hash']; // Hash da senha armazenada no DB

        // Verifica a senha usando password_verify()
        // Isso compara a senha digitada com o hash armazenado
        if (password_verify($password_input, $stored_password_hash)) {
            // Senha correta! Login bem-sucedido.
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // Redireciona o usuário para uma página de sucesso (ex: painel.php)
            header("Location: painel.php");
            exit(); // Garante que o script pare de executar após o redirecionamento
        } else {
            // Senha incorreta
            $message = "Usuário ou senha inválidos.";
        }
    } else {
        // Usuário não encontrado
        $message = "Usuário ou senha inválidos.";
    }
}

// Fecha a conexão com o banco de dados
$conn->close();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Login - FLENGSS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #000000; /* Matches the black background of the logo */
        }
        .login-container {
            background-color: #1a1a1a; /* Dark gray for contrast, similar to a tech theme */
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
        }
        .btn.primary-btn {
            background-color: #00AEEF; /* Matches the logo's blue color */
            transition: background-color 0.2s;
        }
        .btn.primary-btn:hover {
            background-color: #008BCF; /* Slightly darker blue for hover effect */
        }
        .btn.secondary-btn {
            background-color: #333333; /* Darker gray for secondary button */
            color: #ffffff;
            transition: background-color 0.2s;
        }
        .btn.secondary-btn:hover {
            background-color: #444444; /* Slightly lighter gray for hover */
        }
        .forgot-password {
            color: #00AEEF; /* Matches the logo's blue */
        }
        .forgot-password:hover {
            text-decoration: underline;
        }
        input, label {
            color: #ffffff; /* White text for contrast on dark background */
        }
        input {
            background-color: #2a2a2a; /* Dark input background */
            border-color: #444444; /* Subtle border */
        }
        input:focus {
            outline: none;
            border-color: #00AEEF; /* Blue focus ring to match logo */
            box-shadow: 0 0 0 2px rgba(0, 174, 239, 0.3);
        }
    </style>
</head>
<body class="font-sans">
    <header class="bg-black text-white p-4 flex justify-between items-center">
        <div class="flex items-center">
            <img src="./img/unnamed.png" alt="FLENGSS Logo" class="h-10 mr-2">
            <h1 class="text-2xl font-bold uppercase">FLENGSS</h1>
        </div>
    </header>

    <div class="login-container max-w-md mx-auto mt-10 p-6">
        <img src="./img/unnamed.png" alt="FLENGSS Logo" class="h-12 mx-auto mb-4">
        <h2 class="text-2xl font-bold text-center text-white uppercase">Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="input-group mb-4">
                <label for="username" class="block text-sm font-medium">Usuário:</label>
                <input type="text" id="username" name="username" required class="w-full p-2 border rounded">
            </div>
            <div class="input-group mb-4">
                <label for="password" class="block text-sm font-medium">Senha:</label>
                <input type="password" id="password" name="password" required class="w-full p-2 border rounded">
            </div>
            <button type="submit" class="btn primary-btn w-full p-2 text-white rounded">Acessar</button>
            <a href="#" class="forgot-password block text-center text-sm mt-2" id="forgotPassword">Esqueci minha senha</a>
            <button type="button" class="btn secondary-btn w-full p-2 mt-2"><a href="cads.html" class="text-white no-underline">Cadastre-se</a></button>
        </form>
        <div id="message" class="message mt-4 text-center text-sm text-red-500">
            <?php
                if (isset($message)) {
                    echo $message;
                }
            ?>
        </div>
    </div>

    </body>
</html>