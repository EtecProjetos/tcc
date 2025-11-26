<?php
session_start();
include '../../../Back/conexao.php';

if (!isset($_SESSION['aluno_id'])) {
    header("Location: loginAluno.php");
    exit();
}

$aluno_id = $_SESSION['aluno_id'];
$jogos = [];
$turma_id = null;
$turma_nome = 'Sem turma';

// Pega a turma do aluno
$sql_turma = "SELECT turma_id FROM alunos WHERE id = ?";
$stmt = $conn->prepare($sql_turma);
$stmt->bind_param("i", $aluno_id);
$stmt->execute();
$res_turma = $stmt->get_result();
if ($res_turma && $res_turma->num_rows > 0) {
    $row = $res_turma->fetch_assoc();
    $turma_id = $row['turma_id'];
}

// Pega o nome da turma
if ($turma_id) {
    $sql_nome_turma = "SELECT nome FROM turmas WHERE id = ?";
    $stmt_nome = $conn->prepare($sql_nome_turma);
    $stmt_nome->bind_param("i", $turma_id);
    $stmt_nome->execute();
    $res_nome = $stmt_nome->get_result();
    if ($res_nome && $res_nome->num_rows > 0) {
        $turma_nome = $res_nome->fetch_assoc()['nome'];
    }
    $stmt_nome->close();
}

// Busca jogos futuros da turma
if ($turma_id) {
    $sql_jogos = "
        SELECT * FROM jogos 
        WHERE turma_id = ? AND data >= CURDATE()
        ORDER BY data, horario
    ";
    $stmt_j = $conn->prepare($sql_jogos);
    $stmt_j->bind_param("i", $turma_id);
    $stmt_j->execute();
    $res_j = $stmt_j->get_result();
    while ($row = $res_j->fetch_assoc()) {
        $jogos[] = $row;
    }
    $stmt_j->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Jogos</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../../styles/styleBase.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
<link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">

<style>
body {
    margin: 0;
    padding: 20px 20px 100px;
    font-family: 'Fredoka', sans-serif;
    background: linear-gradient(to bottom, #6a0dad 0%, #000 100%);
    color: #fff;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.logo-header {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px 0;
    width: 100%;
}

.logo-header .logo {
    width: 140px;
    height: auto;
}

#jogos-container {
    width: 100%;
    max-width: 720px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.card-jogo {
    background: rgba(138, 58, 185, 0.9);
    border-radius: 12px;
    padding: 20px 25px;
    display: flex;
    flex-direction: column;
    gap: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    transition: background-color 0.3s ease;
}

.card-jogo:hover {
    background-color: #b265d1;
    box-shadow: 0 0 15px 5px rgba(0,0,0,0.7);
}

.versus {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    font-weight: bold;
    flex-wrap: nowrap;
}

.time-bloco {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.logo-time, .logo {
    width: 70px;
    height: 70px;
    object-fit: contain; /* mantém a proporção sem cortar */
    background: transparent; /* garante fundo transparente */
}

.bi-x-lg {
    font-size: 36px;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-shrink: 0;
}

.infos-jogo {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.info {
    background-color: rgba(48,0,67,0.8);
    padding: 12px 15px;
    font-weight: bold;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 10px;
    border-radius: 8px;
    justify-content: flex-start;
}

@media (max-width: 600px) {
    .versus { gap: 12px; font-size: 0.9rem; }
    .logo-time, .logo { width: 60px; height: 60px; }
    .bi-x-lg { font-size: 28px; }
    .infos-jogo { display: flex; flex-direction: column; }
}
</style>
</head>
<body>

<header class="logo-header">
    <img src="../../imgs/logo.png" alt="New Football Logo" class="logo" />
</header>

<div id="jogos-container">
    <?php if (count($jogos) > 0): ?>
        <?php foreach ($jogos as $jogo): ?>
            <div class="card-jogo">
                <div class="versus">
                    <div class="time-bloco">
                        <img src="../../imgs/logo.png" alt="New Football Logo" class="logo" />
                        <span class="time-nome">NEW FOOTBALL</span>
                    </div>
                    <div class="time-bloco">
                       <img src="../../../Front/<?= htmlspecialchars($jogo['logo_url']) ?>" class="logo-time">

                        <span class="time-nome"><?= mb_strtoupper(htmlspecialchars($jogo['adversario']), 'UTF-8') ?></span>
                    </div>
                </div>

<div class="infos-jogo">
    <div class="info"><i class="bi bi-house"></i> LOCAL: <?= mb_strtoupper(htmlspecialchars($jogo['local']), 'UTF-8') ?></div>
    <div class="info"><i class="bi bi-clock"></i> HORÁRIO: <?= date("H:i", strtotime($jogo['horario'])) ?></div>
    <div class="info"><i class="bi bi-calendar-event"></i> DATA: <?= date('d/m/Y', strtotime($jogo['data'])) ?></div>
    <div class="info"><i class="bi bi-list"></i> CATEGORIA: <?= mb_strtoupper(htmlspecialchars($jogo['categoria']), 'UTF-8') ?></div>
    <div class="info"><i class="bi bi-trophy"></i> TIPO: <?= mb_strtoupper(htmlspecialchars($jogo['tipo']), 'UTF-8') ?></div>
</div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center; color:white;">NENHUM JOGO DISPONÍVEL PARA SUA TURMA.</p>
    <?php endif; ?>
</div>

<div id="nav-placeholder"></div>
<script src="../../js/nav.js"></script>
</body>
</html>
