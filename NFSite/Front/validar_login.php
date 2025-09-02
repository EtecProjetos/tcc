<?php
session_start();
require_once __DIR__ . '/../Back/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: loginAluno.php'); exit;
}

$tipo  = $_POST['tipo'] ?? 'aluno'; // deixei pronto pra suportar professor no futuro
$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

if ($tipo !== 'aluno') {
  $_SESSION['erro_login'] = 'Tipo de login inválido.';
  header('Location: loginAluno.php'); exit;
}

$sql  = "SELECT id, nome, email, senha, turma_id FROM alunos WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$res  = $stmt->get_result();

if ($res && $res->num_rows === 1) {
  $u = $res->fetch_assoc();
  $senha_db = $u['senha'];

  // Aceita: texto puro (banco antigo), md5, e password_hash
  $ok = false;
  if ($senha === $senha_db) {
    $ok = true;
  } elseif (md5($senha) === $senha_db) {
    $ok = true;
  } elseif (password_verify($senha, $senha_db)) {
    $ok = true;
  }

  if ($ok) {
    $_SESSION['aluno_id']   = (int)$u['id'];
    $_SESSION['aluno_nome'] = $u['nome'];
    $_SESSION['aluno_email']= $u['email'];
    $_SESSION['turma_id']   = $u['turma_id'];

    header('Location: home_aluno.php'); exit;
  }
}

// falhou
$_SESSION['erro_login'] = 'E-mail ou senha incorretos.';
header('Location: loginAluno.php'); exit;
