<?php
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: loginProfessor.php");
    exit();
}

// Inclui a conexão com o banco
include '../Back/conexao.php';

$professor_nome = '';

// Busca o nome do professor no banco usando prepared statement
$professor_id = $_SESSION['professor_id'];
$stmt = $conn->prepare("SELECT nome FROM professores WHERE id = ?");
$stmt->bind_param("i", $professor_id);
$stmt->execute();
$stmt->bind_result($nome);
if ($stmt->fetch()) {
    $professor_nome = $nome;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>New Football - Professor</title>

  <link rel="stylesheet" href="styles/styleHomeProfessor.css" />
  <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&display=swap" rel="stylesheet" />
</head>
<body>

  <!-- Logo -->
  <header class="logo-header">
    <img src="imgs/logo.png" alt="New Football Logo" class="logo" />
  </header>

  <!-- Conteúdo principal -->
  <main class="content">
    <section class="intro-text">
      <!-- Saudações personalizadas -->
      <h2 style="color: #FFD700; font-weight: 700;">
        Bem-vindo, <?= htmlspecialchars($professor_nome) ?>!
      </h2>

      <h1>
        O New Football é o lugar ideal para os professores treinarem e inspirarem os futuros craques com metodologia moderna.
      </h1>
      <h2>Aqui você forma os roxinhos do amanhã</h2>
    </section>
  </main>

  <!-- Placeholder da navbar inferior -->
  <div id="nav-placeholder"></div>

  <!-- Scripts -->
  <script src="js/nav_professor.js"></script>
</body>
</html>
