<?php
session_start();
include '../../../Back/conexao.php';

// Verifica se o admin está logado
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: admin.php");
    exit;
}

// Pega o ID do campeonato
$campeonato_id = isset($_GET['campeonato_id']) ? intval($_GET['campeonato_id']) : 0;

if ($campeonato_id === 0) {
    echo "Campeonato não especificado.";
    exit;
}

// Busca informações do campeonato
$stmt = $conn->prepare("SELECT nome, data_inicio, idade_maxima FROM campeonatos WHERE id = ?");
$stmt->bind_param("i", $campeonato_id);
$stmt->execute();
$camp = $stmt->get_result()->fetch_assoc();

// Busca convocados
$sql = "
    SELECT c.id AS convocacao_id, a.nome AS aluno_nome, p.nome AS professor_nome
    FROM convocacoes c
    LEFT JOIN alunos a ON c.aluno_id = a.id
    LEFT JOIN professores p ON c.professor_id = p.id
    WHERE c.campeonato_id = ?
    ORDER BY a.nome ASC, p.nome ASC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $campeonato_id);
$stmt->execute();
$convocados = $stmt->get_result();

$alunos = [];
$professores = [];
while ($row = $convocados->fetch_assoc()) {
    if (!empty($row['aluno_nome'])) $alunos[] = $row['aluno_nome'];
    if (!empty($row['professor_nome'])) $professores[] = $row['professor_nome'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Convocados - <?= htmlspecialchars($camp['nome']) ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css">
<link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">
<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #000000, #4c0070);
    color: white;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
}
.page-wrapper { max-width: 800px; margin: 0 auto; }
h2 { text-align:center; color:#FFD700; margin-bottom:25px; }
.card { background: rgba(255,255,255,0.1); border-radius:12px; padding:20px; margin-bottom:15px; }
.card .titulo { font-weight:bold; font-size:1.2rem; color:#FFD700; margin-bottom:10px; }
.card ul { padding-left: 20px; margin:0; }
.card li { margin-bottom:5px; }
.no-convocados { text-align:center; color:#FFD700; font-weight:bold; margin-top:20px; }
</style>
</head>
<body>

<div class="page-wrapper">
    <h2>Convocados - <?= htmlspecialchars($camp['nome']) ?></h2>

    <div class="card">
        <div class="titulo">Professores Convocados</div>
        <?php if(count($professores) > 0): ?>
            <ul>
                <?php foreach($professores as $p): ?>
                    <li><?= htmlspecialchars($p) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="no-convocados">Nenhum professor convocado.</p>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="titulo">Alunos Convocados</div>
        <?php if(count($alunos) > 0): ?>
            <ul>
                <?php foreach($alunos as $a): ?>
                    <li><?= htmlspecialchars($a) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="no-convocados">Nenhum aluno convocado.</p>
        <?php endif; ?>
    </div>

    <div style="text-align:center; margin-top:20px;">
        <a href="listar_campeonato.php" style="color:#FFD700; font-weight:bold; text-decoration:none;">← Voltar</a>
    </div>
</div>

</body>
</html>
