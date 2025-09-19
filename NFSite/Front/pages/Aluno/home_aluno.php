<?php
session_start();

// Verifica se o aluno está logado
if (!isset($_SESSION['aluno_id'])) {
    header("Location: loginAluno.php");
    exit();
}

// Conexão com o banco
include '../../../Back/conexao.php';  // Corrigido para 3 níveis acima

$aluno_nome = '';
$aluno_id = $_SESSION['aluno_id'];

// Consulta para obter o nome do aluno
$stmt = $conn->prepare("SELECT nome FROM alunos WHERE id = ?");
$stmt->bind_param("i", $aluno_id);
$stmt->execute();
$stmt->bind_result($nome);
if ($stmt->fetch()) {
    $aluno_nome = $nome;
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
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">
</head>
<body>
  <!-- Logo -->
  <header class="logo-header">
    <img src="../../imgs/logo.png" alt="New Football Logo" class="logo" />
  </header>

  <!-- Incluir a Navbar -->
  <?php include './nav.php'; ?>

  <!-- Conteúdo principal -->
  <main class="content">
    <section class="intro-text">
      <h2 style="color: #FFD700; font-weight: 700;">
        Bem-vindo, <?= htmlspecialchars($aluno_nome) ?>!
      </h2>
      <h1>O New Football é o lugar perfeito para você aprender, evoluir e se divertir com o futebol.</h1>
      <h2>Aqui você cresce como craque e como pessoa</h2>
    </section>
  </main>

  <!-- Placeholder da navbar inferior -->
  <div id="nav-placeholder"></div>

</body>
</html>
