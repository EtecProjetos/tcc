<?php
session_start();
include '../Back/conexao.php';

$email = $_POST['email'];
$tipo  = $_POST['tipo'];

// Decide tabela
$tabela = $tipo == "professor" ? "professores" : "alunos";

$sql = "SELECT id FROM $tabela WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    $_SESSION['msg'] = "E-mail não encontrado!";
    header("Location: esqueci_senha.php");
    exit;
}

$user = $result->fetch_assoc();
$usuario_id = $user['id'];

// Gera token
$token = bin2hex(random_bytes(32));
$expiracao = date("Y-m-d H:i:s", strtotime("+1 hour"));

// Salva token
$sqlInsert = "INSERT INTO senha_reset (usuario_id, tipo, token, expiracao, usado) VALUES (?, ?, ?, ?, 0)";
$stmtInsert = $conn->prepare($sqlInsert);
$stmtInsert->bind_param("isss", $usuario_id, $tipo, $token, $expiracao);
$stmtInsert->execute();

// Ajuste o caminho conforme seu projeto!
$link = "http://localhost/NFSite/Front/recuperar_senha.php?token=$token";

// Envia com mail()
$assunto = "Recuperação de Senha - New Football";
$mensagem = "Clique no link abaixo para redefinir sua senha:\n\n$link\n\nEste link expira em 1 hora.";
$headers = "From: no-reply@newfootball.com";

if(mail($email, $assunto, $mensagem, $headers)){
    $_SESSION['msg'] = "Um link de recuperação foi enviado para seu e-mail.";
} else {
    $_SESSION['msg'] = "Falha ao enviar e-mail. Verifique seu servidor.";
}

header("Location: esqueci_senha.php");
exit;
