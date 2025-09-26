<?php
session_start();
session_unset();
session_destroy();

// Evita cache do navegador
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Redireciona para a página inicial
header("Location: Divulgacao_New/index.php");
exit();
?>
