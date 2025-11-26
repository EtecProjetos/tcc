<?php
session_start();

// Limpa todas as variáveis de sessão
$_SESSION = [];

// Destrói a sessão
session_destroy();

// Redireciona para a página de login do professor
header("Location: ../../Divulgacao_New/index.php");
exit();
