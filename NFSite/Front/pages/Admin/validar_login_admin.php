<?php
session_start();

// Defina aqui o usuário e senha do CEO
$admin_user = "ceo";
$admin_pass = "123456"; // altere para a senha desejada

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if ($username === $admin_user && $senha === $admin_pass) {
        $_SESSION['admin_logado'] = true;
        header("Location: home_admin.php");
        exit;
    } else {
        $_SESSION['erro_login'] = "Usuário ou senha incorretos!";
        header("Location: admin.php");
        exit;
    }
} else {
    // Acesso direto não permitido
    header("Location: admin.php");
    exit;
}
?>