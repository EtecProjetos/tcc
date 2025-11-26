<?php
session_start();

// Verifica se o aluno está logado
if (!isset($_SESSION['aluno_id'])) {
    header("Location: loginAluno.php");
    exit();
}

// Conexão com o banco
include '../../../Back/conexao.php';

$aluno_nome = '';
$turma_nome = 'Sem turma';
$aluno_id   = $_SESSION['aluno_id'];

// Consulta para obter o nome do aluno e o nome da turma
$stmt = $conn->prepare("
    SELECT a.nome AS aluno_nome, t.nome AS turma_nome
    FROM alunos a
    LEFT JOIN turmas t ON a.turma_id = t.id
    WHERE a.id = ?
");
$stmt->bind_param("i", $aluno_id);
$stmt->execute();
$stmt->bind_result($nome, $turma);
if ($stmt->fetch()) {
    $aluno_nome = $nome;
    if ($turma) $turma_nome = $turma;
}
$stmt->close();

?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Home</title>

<link rel="stylesheet" href="../../styles/styleHomeAluno.css" />
<link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">
</head>
<body>
  <!-- Logo -->
  <header class="logo-header">
    <img src="../../imgs/logo.png" alt="New Football Logo" class="logo" />
  </header>

  <!-- Conteúdo principal -->
  <main class="content">
    <section class="intro-text">
      <h2>Bem-vindo, <?= htmlspecialchars($aluno_nome) ?>!</h2>
      <h1>O New Football é o lugar perfeito para você aprender, evoluir e se divertir com o futebol.</h1>
      <h2>Aqui você cresce como craque e como pessoa</h2>
    </section>

    <!-- Aqui você pode adicionar cards ou links rápidos -->
  </main>

<div id="nav-placeholder"></div>

<script src="../../js/nav.js"></script>

</body>
</html>
