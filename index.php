<?php
// Inicia a sessão - importante para manter o estado do login
session_start();

// Define um email e palavra-passe de exemplo para o administrador
// Num cenário real, estes dados viriam de uma base de dados e as senhas seriam "hasheadas"
$email_correto = "admin@freelancers.com";
$senha_correta = "admin123"; // Lembre-se de usar hashing para senhas em produção
$destino_admin = "menu.html"; // Página de destino para o admin

// Variáveis para armazenar mensagens de erro ou sucesso
$mensagem_php = ""; // Renomeado para evitar conflito com qualquer variável JS

// Verifica se o formulário foi submetido usando o método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados do formulário
    // É importante validar e sanitizar os inputs em aplicações reais
    $email_submetido = isset($_POST['email']) ? trim($_POST['email']) : '';
    $senha_submetida = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Validação básica (não vazios)
    if (empty($email_submetido) || empty($senha_submetida)) {
        $mensagem_php = "Por favor, preencha o email e a palavra-passe.";
    } else {
        // Verifica se as credenciais estão corretas
        // Para este exemplo, estamos apenas verificando o admin.
        // Numa aplicação real, você consultaria uma base de dados.
        if ($email_submetido === $email_correto && $senha_submetida === $senha_correta) {
            // Credenciais corretas
            $_SESSION['loggedin'] = true;
            $_SESSION['email'] = $email_submetido;
            $_SESSION['user_type'] = 'admin'; // Pode adicionar tipo de utilizador se necessário

            // Redireciona para a página de destino do admin
            header("Location: " . $destino_admin);
            exit; // Termina o script após o redirecionamento

        } else {
            // Credenciais incorretas
            // Poderia adicionar lógica para outros tipos de utilizador aqui se viessem da BD
            $mensagem_php = "Email ou palavra-passe incorretos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancers - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif; /* Adicionando uma fonte padrão similar ao exemplo anterior */
        }
        /* Estilos base para campos de input */
        .input-field {
            width: 100%; /* Tailwind w-full */
            padding: 0.75rem; /* Tailwind p-3 */
            border: 1px solid #d1d5db; /* Tailwind border border-gray-300 */
            border-radius: 0.375rem; /* Tailwind rounded-md */
            /* Faltava o fechamento do seletor .input-field */
        }
        .input-field:focus {
            box-shadow: 0 0 0 2px #3b82f6; /* Tailwind focus:ring-2 focus:ring-blue-500 */
            border-color: #3b82f6; /* Tailwind focus:border-blue-500 */
            outline: none; /* Adicionado para remover o outline padrão do browser no focus */
        }

        /* Estilos para os botões de cadastro (verde) - Mantido caso precise no futuro */
        .btn-green-cadastro {
            background-color: #22c55e; /* Tailwind bg-green-500 */
            color: #fff; /* Tailwind text-white */
            padding: 0.75rem; /* Tailwind p-3 */
            border-radius: 0.375rem; /* Tailwind rounded-md */
            transition: background-color 0.3s, color 0.3s;
            width: 100%; /* Tailwind w-full */
            display: block; /* Tailwind block */
            text-align: center; /* Tailwind text-center */
        }
        .btn-green-cadastro:hover {
            background-color: #16a34a; /* Tailwind hover:bg-green-600 */
        }

        /* Estilos para o botão "Login" (azul) do header - serve como "Voltar" visualmente */
        .btn-blue-voltar {
            background-color: #2563eb; /* Tailwind bg-blue-600 */
            color: #fff; /* Tailwind text-white */
            padding: 0.5rem 1rem; /* Tailwind p-2 px-4 */
            border-radius: 0.375rem; /* Tailwind rounded-md */
            transition: background-color 0.3s, color 0.3s;
            text-decoration: none; /* Para garantir que se pareça com um botão */
        }
        .btn-blue-voltar:hover {
            background-color: #1d4ed8; /* Tailwind hover:bg-blue-700 */
        }

        /* Oculta o overlay do menu mobile por padrão */
        #mobile-menu-overlay {
            display: none;
        }

        /* Exibe o overlay do menu mobile quando a classe 'open' é adicionada */
        #mobile-menu-overlay.open {
            display: flex;
            flex-direction: column;
            position: fixed; /* Garante que o menu cubra a tela toda */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9); /* Fundo escuro semi-transparente */
            z-index: 1000; /* Garante que fique acima de todo o conteúdo */
            justify-content: center;
            align-items: center;
            animation: slideIn 0.3s forwards; /* Animação para entrada */
        }
        
        @keyframes slideIn { /* Corrigido nome da animação */
            from {
                transform: translateX(100%); /* Começa fora da tela à direita */
            }
            to {
                transform: translateX(0); /* Desliza para a posição normal */
            }
        }

        /* Animação de saída do menu mobile (opcional, para um fechamento mais suave) */
        @keyframes slideOut {
            from {
                transform: translateX(0);
            }
            to {
                transform: translateX(100%);
            }
        }

        #mobile-menu-overlay.closing { /* Estilo para fechar */
            animation: slideOut 0.3s forwards;
        }

        /* Estilos dos links dentro do menu mobile */
        #mobile-menu-overlay .nav-links-mobile a {
            color: #fff; /* Tailwind text-white */
            font-size: 1.5rem; /* Tailwind text-2xl */
            padding-top: 1rem; /* Tailwind py-4 */
            padding-bottom: 1rem;
            transition: color 0.2s;
            display: block; /* Para melhor espaçamento e clique */
            text-align: center;
        }
        #mobile-menu-overlay .nav-links-mobile a:hover {
            color: #60a5fa; /* Tailwind hover:text-blue-400 */
        }

        /* Estilo para a mensagem de feedback do PHP */
        .feedback-message-php {
            padding: 0.75rem 1rem; /* p-3 px-4 */
            margin-bottom: 1.5rem; /* mb-6 */
            border-radius: 0.375rem; /* rounded-md */
            font-weight: 500; /* medium */
            text-align: center;
        }
        .success-php {
            background-color: #d1fae5; /* green-100 */
            color: #065f46; /* green-700 */
            border: 1px solid #34d399; /* green-400 */
        }
        .error-php {
            background-color: #fee2e2; /* red-100 */
            color: #991b1b; /* red-700 */
            border: 1px solid #f87171; /* red-400 */
        }
        /* Adicionando Inter font */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    </style>
</head>
<body class="font-sans bg-gray-100 flex flex-col min-h-screen" style="font-family: 'Inter', sans-serif;">
    <header class="bg-gray-900 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-3xl font-bold uppercase">Freelancers</h1>
            <nav class="hidden md:flex space-x-4 items-center">
                <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="btn-blue-voltar">Login</a>
                <a href="cliente_cadastro.html" class="text-white hover:text-blue-400 transition-colors duration-200">Cadastre-se como Cliente</a>
                <a href="prof_cadastro.html" class="text-white hover:text-blue-400 transition-colors duration-200">Cadastre-se como Profissional</a>
            </nav>
            <button id="mobile-menu-button" class="md:hidden text-white focus:outline-none">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </header>

    <div id="mobile-menu-overlay" class="md:hidden">
        <button id="close-mobile-menu" class="absolute top-6 right-6 text-white text-4xl focus:outline-none z-10">
            &times;
        </button>
        <nav class="nav-links-mobile flex flex-col items-center w-full">
            <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="btn-blue-voltar my-3 px-6">Login</a>
            <a href="cliente_cadastro.html" class="my-3">Cadastre-se como Cliente</a>
            <a href="prof_cadastro.html" class="my-3">Cadastre-se como Profissional</a>
        </nav>
    </div>

    <main class="flex-grow flex items-center justify-center p-4">
        <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md text-center">
            <h2 class="text-4xl font-bold text-gray-800 mb-6">Bem-vindo(a)!</h2>
            <p class="text-gray-600 mb-8">Encontre o profissional certo ou ofereça seus serviços.</p>

            <?php
            // Exibe a mensagem de feedback do PHP, se houver alguma
            if (!empty($mensagem_php)) {
                // Determina a classe CSS com base no tipo de mensagem
                $tipo_mensagem_php = (strpos(strtolower($mensagem_php), 'bem-sucedido') !== false || strpos(strtolower($mensagem_php), 'bem-vindo') !== false) ? 'success-php' : 'error-php';
                echo "<div class='feedback-message-php " . $tipo_mensagem_php . "'>" . htmlspecialchars($mensagem_php) . "</div>";
            }
            ?>
            
            <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true): ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="space-y-6">
                <div>
                    <label for="loginEmail" class="sr-only">Email:</label>
                    <input type="email" id="loginEmail" name="email" placeholder="Seu email (admin@freelancers.com)"
                           class="input-field" required>
                </div>
                <div>
                    <label for="loginPassword" class="sr-only">Senha:</label>
                    <input type="password" id="loginPassword" name="password" placeholder="Sua senha (admin123)"
                           class="input-field" required>
                    <p class="text-left mt-2">
                        <a href="recuperar-senha.html" class="text-blue-600 hover:underline text-sm">Esqueceu a senha?</a>
                    </p>
                </div>
                <button type="submit" class="bg-blue-500 text-white p-3 rounded-md hover:bg-green-600 transition duration-300 ease-in-out w-full font-semibold">Entrar</button>
            </form>
            <?php else: ?>
                <div class="text-center">
                    <p class="text-gray-700 text-lg">Você já está logado como <?php echo htmlspecialchars($_SESSION['email']); ?>.</p>
                    <p class="mt-2 text-gray-600">Você será redirecionado para o seu painel.</p>
                    <a href="logout.php" class="mt-6 inline-block bg-red-500 text-white py-2 px-6 rounded-md hover:bg-red-600 transition duration-150">Sair</a>
                    <?php
                        // Script para redirecionar se já estiver logado e tentar acessar a página de login novamente
                        // Pode ser útil se você quiser forçar o utilizador para fora da página de login
                        // if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin') {
                        //    echo '<script>setTimeout(function(){ window.location.href = "'.$destino_admin.'"; }, 2000);</script>';
                        // }
                    ?>
                     <!-- Para o link de logout funcionar, você precisaria criar um arquivo logout.php:
                    <?php
                    /*
                    // logout.php
                    session_start();
                    session_unset();
                    session_destroy();
                    header("Location: index.php"); // Ou a sua página de login principal
                    exit;
                    */
                    ?>
                    -->
                </div>
            <?php endif; ?>
            
            <p class="text-xs text-gray-500 mt-8">
                Para teste (Admin): Email: <strong>admin@freelancers.com</strong> | Senha: <strong>admin123</strong>
            </p>
        </div>
    </main>
    <footer class="bg-gray-900 text-white p-4 text-center text-sm mt-auto"> <div class="container mx-auto">&copy; <?php echo date("Y"); ?> Freelancers. Todos os direitos reservados.</div>
    </footer>

   <script>
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
    const closeMobileMenuButton = document.getElementById('close-mobile-menu'); // Corrigido o nome da variável

    if (mobileMenuButton && mobileMenuOverlay && closeMobileMenuButton) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenuOverlay.classList.remove('closing'); // Garante que não está a fechar
            mobileMenuOverlay.classList.add('open');
        });

        closeMobileMenuButton.addEventListener('click', () => { // Corrigido para usar o botão de fechar
            mobileMenuOverlay.classList.add('closing');
            // Espera a animação de saída terminar antes de remover a classe 'open'
            mobileMenuOverlay.addEventListener('animationend', function handler() {
                mobileMenuOverlay.classList.remove('open');
                mobileMenuOverlay.classList.remove('closing');
                mobileMenuOverlay.removeEventListener('animationend', handler);
            });
        });

        // Fecha o menu ao clicar num link dentro dele
        mobileMenuOverlay.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                if (mobileMenuOverlay.classList.contains('open')) {
                    closeMobileMenuButton.click(); // Simula o clique no botão de fechar para animação
                }
            });
        });
    }

    // A lógica de login via JavaScript foi removida, pois o PHP está a tratar disso.
    // O código abaixo era a simulação de login em JS:
    /*
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault(); 

        const email = document.getElementById('loginEmail').value.trim();
        const password = document.getElementById('loginPassword').value.trim();

        if (email === '' || password === '') {
            alert('Por favor, preencha todos os campos.'); // Substituir alert por um modal/div de mensagem
            return;
        }

        const userDB = [
            { email: 'cliente@email.com', senha: '123456', destino: 'cliente.html' },
            { email: 'profissional@email.com', senha: 'abcdef', destino: 'prof.html' },
            { email: 'admin@freelancers.com', senha: 'admin123', destino: 'menu.html' } 
        ];

        const usuarioValido = userDB.find(user => user.email === email && user.senha === password);

        if (usuarioValido) {
            window.location.href = usuarioValido.destino;
        } else {
            alert('Email ou senha incorretos.'); // Substituir alert por um modal/div de mensagem
        }
    });
    */
</script>

</body>
</html>
