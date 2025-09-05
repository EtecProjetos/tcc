<?php
session_start();

// Impede cache da página
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Verifica se o aluno está logado
if (!isset($_SESSION['aluno_id'])) {
    // Redireciona para a página de login se não estiver logado
    header("Location: loginAluno.php");
    exit();
}

// Inclui a conexão com o banco (ajuste o caminho se necessário)
include '../Back/conexao.php';

$aluno_nome = '';

// Busca o nome do aluno no banco usando prepared statement
$aluno_id = $_SESSION['aluno_id'];
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
  <title>New Football</title>

  <!-- CSS específico para a home do aluno -->
  <link rel="stylesheet" href="styles/styleHomeAluno.css" />
  <!-- Fonte Fredoka do Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&display=swap" rel="stylesheet" />

</head>
<body>

  <!-- Logo -->
  <header class="logo-header">
    <img src="imgs/logo.png" alt="New Football Logo" class="logo" />
  </header>

  <!-- Texto principal -->
  <main class="content">
    <section class="intro-text">
      <!-- Saudações personalizadas com escape para evitar XSS -->
      <h2 style="color: #FFD700; font-weight: 700;">
        Bem-vindo, <?= htmlspecialchars($aluno_nome) ?>!
      </h2>

      <h1>
        O New Football é o lugar perfeito para os pequenos craques se
        divertirem e aprenderem com treinos adaptados para todas as idades.
      </h1>
      <h2>Aqui você se torna um roxinho</h2>
    </section>
  </main>

  <!-- Espaço para carregar a navbar via JS -->
  <div id="nav-placeholder"></div>

  <!-- Script externo para controle da navbar -->
  <script src="js/nav.js"></script>
  
</body>
</html>
