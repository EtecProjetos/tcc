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

        // Caminho correto para o Front
        header("Location: ../../Front/pages/Admin/home_admin.php");
        exit;

    } else {

        $_SESSION['erro_login'] = "Usuário ou senha incorretos!";

        // Caminho correto para a página de login do admin no Front
        header("Location: ../../Front/pages/Admin/admin.php");
        exit;
    }

} else {

    // Evita acesso direto
    header("Location: ../../Front/pages/Admin/admin.php");
    exit;
}
