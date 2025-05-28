<?php
session_start(); // Inicia a sessão para poder destruí-la
session_unset(); // Remove todas as variáveis de sessão
session_destroy(); // Destrói a sessão
header("Location: login_freelancers.php"); // Redireciona de volta para a página de login (ajuste o nome do arquivo se necessário)
exit;
?>