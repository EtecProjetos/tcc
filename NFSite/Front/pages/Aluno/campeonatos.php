<?php
session_start();

// Verifica se o aluno está logado
if (!isset($_SESSION['aluno_id'])) {
    header("Location: Divulgacao_New/index.php");
    exit();
}

// Inclui a conexão com o banco
include '../../../Back/conexao.php';

$aluno_nome = '';

// Busca o nome do aluno usando a tabela pessoa
$aluno_id = $_SESSION['aluno_id'];
$stmt = $conn->prepare("
    SELECT p.nome
    FROM alunos a
    JOIN pessoa p ON a.id = p.id
    WHERE a.id = ?
");
$stmt->bind_param("i", $aluno_id);
$stmt->execute();
$stmt->bind_result($nome);
if ($stmt->fetch()) {
    $aluno_nome = $nome;
}
$stmt->close();

// Busca os campeonatos
$sql = "SELECT * FROM campeonatos ORDER BY data ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Campeonatos</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&display=swap" rel="stylesheet" />
<link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">
<style>
body {
    font-family: 'Fredoka', sans-serif;
    background: linear-gradient(to bottom, #6a0dad 0%, #000000 100%);
    color: #fff;
    margin: 0;
    padding: 0;
}
header.logo-header { margin-bottom: 30px; text-align: center; }
header.logo-header .logo { width: 180px; }
.campeonatos-container {
    max-width: 900px;
    margin: 20px auto;
    padding: 20px;
    background: rgba(255,255,255,0.05);
    border-radius: 15px;
}
h1.campeonatos-title {
    color: #FFD700;
    text-align: center;
    margin-bottom: 20px;
}
.btn-voltar {
    display: inline-block;
    margin-bottom: 20px;
    text-decoration: none;
    color: #fff;
    background-color: #6a0dad;
    padding: 8px 15px;
    border-radius: 5px;
}
.campeonato-card {
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
    background-color: rgba(0,0,0,0.2);
    transition: transform 0.2s;
    color: #fff;
}
.campeonato-card:hover { transform: scale(1.02); }
.campeonato-card h2 { margin: 0 0 5px 0; color: #FFD700; }
.campeonato-card p { margin: 3px 0; color: #ddd; }
.futuro { border-left: 5px solid #00ff99; }
.passado { border-left: 5px solid #ff6666; color: #bbb; }
</style>
</head>
<body>

<header class="logo-header">
    <img src="../../imgs/logo.png" alt="New Football Logo" class="logo" />
</header>

<main class="content">
    <section class="intro-text">
        <h1 class="campeonatos-title">Campeonatos</h1>
        <a href="home_aluno.php" class="btn-voltar">← Voltar</a>

        <div class="campeonatos-container">
            <?php
            if ($result->num_rows > 0) {
                $hoje = date('Y-m-d');
                while($row = $result->fetch_assoc()) {
                    $classe = ($row['data'] >= $hoje) ? 'futuro' : 'passado';
                    echo '<div class="campeonato-card ' . $classe . '">';
                    echo '<h2>' . htmlspecialchars($row['nome']) . '</h2>';
                    echo '<p><strong>Idade mínima:</strong> ' . htmlspecialchars($row['idade_minima']) . '</p>';
                    echo '<p><strong>Idade máxima:</strong> ' . htmlspecialchars($row['idade_maxima']) . '</p>';
                    echo '<p><strong>Rodadas:</strong> ' . htmlspecialchars($row['rodadas']) . '</p>';
                    echo '<p><strong>Data:</strong> ' . date("d/m/Y", strtotime($row['data'])) . '</p>';
                    echo '</div>';
                }
            } else {
                echo "<p>Nenhum campeonato cadastrado.</p>";
            }
            $conn->close();
            ?>
        </div>
    </section>
</main>

<div id="nav-placeholder"></div>
<script src="../../js/nav.js"></script>

</body>
</html>
