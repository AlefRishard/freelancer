document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const forgotPasswordLink = document.getElementById('forgotPassword');
    const registerButton = document.getElementById('registerButton');
    const messageDiv = document.getElementById('message');

    // Função para exibir mensagens
    const showMessage = (msg, type) => {
        messageDiv.textContent = msg;
        messageDiv.className = `message ${type}`; // Adiciona a classe de tipo (success ou error)
        messageDiv.style.display = 'block'; // Mostra a mensagem
        setTimeout(() => {
            messageDiv.style.display = 'none'; // Esconde a mensagem após 3 segundos
        }, 3000);
    };

    // Evento de submissão do formulário de login
    loginForm.addEventListener('submit', (event) => {
        event.preventDefault(); // Impede o envio padrão do formulário

        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        // Simulação de autenticação (substitua por uma lógica de backend real)
        if (username === 'usuario' && password === 'senha123') {
            showMessage('Login realizado com sucesso!', 'success');
            // Redirecionar para outra página ou área logada
            // window.location.href = 'dashboard.html';
        } else {
            showMessage('Usuário ou senha incorretos.', 'error');
        }
    });

    // Evento para o link "Esqueci minha senha"
    forgotPasswordLink.addEventListener('click', (event) => {
        event.preventDefault(); // Impede o comportamento padrão do link
        showMessage('Você clicou em "Esqueci minha senha". Redirecionando para a página de recuperação...', 'success');
        // Aqui você pode redirecionar para uma página de recuperação de senha
        // window.location.href = 'reset-password.html';
    });

    // Evento para o botão "Cadastre-se"
    registerButton.addEventListener('click', () => {
        showMessage('Você clicou em "Cadastre-se". Redirecionando para a página de cadastro...', 'success');
        // Aqui você pode redirecionar para uma página de cadastro
        // window.location.href = 'register.html';
    });
});