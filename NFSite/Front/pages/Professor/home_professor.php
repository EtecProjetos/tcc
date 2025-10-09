<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: loginProfessor.php");
    exit();
}

// Conexão com o banco
include '../../../Back/conexao.php';

$professor_nome = '';
$turmas = []; // Armazenará as turmas que o professor ministra
$professor_id = $_SESSION['professor_id'];

// Consulta para obter o nome do professor
$stmt = $conn->prepare("SELECT nome FROM professores WHERE id = ?");
$stmt->bind_param("i", $professor_id);
$stmt->execute();
$stmt->bind_result($nome);
if ($stmt->fetch()) {
    $professor_nome = $nome;
}
$stmt->close();

// Consulta para obter as turmas do professor
$stmt2 = $conn->prepare("SELECT nome FROM turmas WHERE professor_id = ?");
$stmt2->bind_param("i", $professor_id);
$stmt2->execute();
$stmt2->bind_result($turma_nome);
while ($stmt2->fetch()) {
    $turmas[] = $turma_nome;
}
$stmt2->close();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Home</title>

<link rel="stylesheet" href="../../styles/styleHomeAluno.css" />
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&display=swap" rel="stylesheet" />
<link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">
</head>
<body>
  <!-- Logo -->
  <header class="logo-header">
    <img src="../../imgs/logo.png" alt="New Football Logo" class="logo" />
  </header>

  <!-- Navbar -->
  <?php include './nav_professor.php'; ?>

  <!-- Conteúdo principal -->
  <main class="content">
    <section class="intro-text">
      <h2>Bem-vindo, <?= htmlspecialchars($professor_nome) ?>!</h2>
      <h1>O New Football te ajuda a organizar seus treinos e gerenciar suas turmas.</h1>
      <h2>Acompanhe seus alunos, registre presença e acompanhe o desempenho.</h2>
      <?php if (!empty($turmas)) : ?>
      <?php endif; ?>
    </section>

    <!-- Aqui você pode adicionar cards ou links rápidos para treinos, jogos, frequência, etc. -->
  </main>
  
  <div id="nav_professor-placeholder"></div>
  <script src="../../js/nav.js"></script>
</body>
</html>
