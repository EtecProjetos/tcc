<?php
session_start();

// Limpa todas as variáveis de sessão
$_SESSION = [];

// Destrói a sessão
session_destroy();

// Redireciona para o index da pasta Divulgacao_New
header("Location: ../../Divulgacao_New/index.php");
exit();
