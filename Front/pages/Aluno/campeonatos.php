<?php
session_start();
include '../../../Back/conexao.php';

if (!isset($_SESSION['aluno_id'])) {
    header("Location: loginAluno.php");
    exit();
}

$aluno_id = $_SESSION['aluno_id'];
$convocados = [];

// Buscar campeonatos em que o aluno foi convocado
$sql = "
    SELECT c.campeonato_id, ca.nome AS campeonato_nome
    FROM convocacoes c
    LEFT JOIN campeonatos ca ON c.campeonato_id = ca.id
    WHERE c.aluno_id = ?
    ORDER BY ca.nome ASC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $aluno_id);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    $convocados[] = $row;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Minhas Convocações - New Football</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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
.page-wrapper {max-width:800px; margin:0 auto;}
.card {background: rgba(255,255,255,0.1); border-radius:12px; padding:20px; margin-bottom:15px;}
.card .titulo {font-weight:bold; font-size:1.2rem; color:#FFD700; margin-bottom:5px;}
.card .info {font-size:1rem; color: white;}
.sem-convocacao {color:#FFD700; font-weight:bold; text-align:center; margin-top:20px;}
</style>
</head>
<body>
<div class="page-wrapper">
    <h2 class="text-center mb-4" style="color:#FFD700;">Minhas Convocações</h2>

    <?php if(count($convocados) > 0): ?>
        <?php foreach($convocados as $c): ?>
            <div class="card">
                <div class="titulo"><?= htmlspecialchars($c['campeonato_nome']) ?></div>
                <div class="info">Você foi convocado para este campeonato.</div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="sem-convocacao">Você ainda não foi convocado para nenhum campeonato.</p>
    <?php endif; ?>
<div id="nav-placeholder"></div>

<script src="../../js/nav.js"></script>
</div>
</body>
</html>
