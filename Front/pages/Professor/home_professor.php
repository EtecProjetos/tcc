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
$stmt2 = $conn->prepare("
    SELECT t.id, t.nome
    FROM turmas t
    INNER JOIN turma_professor tp ON tp.turma_id = t.id
    WHERE tp.professor_id = ?
");

$stmt2->bind_param("i", $professor_id);
$stmt2->execute();
$stmt2->bind_result($turma_id, $turma_nome);
while ($stmt2->fetch()) {
    $turmas[] = ['id' => $turma_id, 'nome' => $turma_nome];
}
$stmt2->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Home </title>

<!-- Google Fonts -->


<!-- Ícones Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">

<link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">

<style>
body {
    margin: 0;
    padding: 0;
    font-family: 'Fredoka', sans-serif;
    background: linear-gradient(to bottom, #6a0dad 0%, #000000 100%);
    color: #fff;
    min-height: 100vh;
}

.logo-header {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 30px 0;
}

.logo {
    width: 130px;
    height: auto;
}

.content {
    padding: 30px 20px 80px;
    text-align: center;
}

.intro-text h1 {
    font-size: 1.4rem;
    font-weight: 600;
    line-height: 1.6;
    margin-bottom: 20px;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
}

.intro-text h2 {
    font-size: 1.6rem;
    font-weight: 700;
    color: #ffe600;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.4);
    margin-bottom: 30px;
}

.turmas-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.turma-card {
    background: linear-gradient(135deg, #8e2de2, #4a00e0);
    border-radius: 12px;
    padding: 15px;
    width: 150px;
    text-align: center;
    box-shadow: 0 3px 8px rgba(0,0,0,0.4);
    transition: transform 0.3s, box-shadow 0.3s;
}

.turma-card:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(0,0,0,0.6);
}

.turma-card h3 {
    color: #FFD700;
    font-size: 1rem;
    margin-bottom: 10px;
}

.turma-card .acoes {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.turma-card .acoes a {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 6px 10px;
    border-radius: 10px;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 600;
    color: #fff;
    transition: 0.2s;
}

.turma-card .acoes a.treino { background: #6a0dad; }
.turma-card .acoes a.treino:hover { background: #8e2de2; }
.turma-card .acoes a.alunos { background: #2980b9; }
.turma-card .acoes a.alunos:hover { background: #3498db; }
.turma-card .acoes a.frequencia { background: #e67e22; }
.turma-card .acoes a.frequencia:hover { background: #f39c12; }

.sem-turmas {
    text-align: center;
    font-size: 1.2rem;
    margin-top: 30px;
    color: #ffeb99;
}

@media (min-width: 768px) {
    .logo { width: 160px; }
    .intro-text h1 { font-size: 1.8rem; }
    .intro-text h2 { font-size: 2rem; }
}

@media (max-width:480px){
    .turmas-container { gap: 12px; }
    .turma-card { width: 120px; padding: 12px; }
    .turma-card h3 { font-size: 0.9rem; }
    .turma-card .acoes a { font-size: 0.75rem; padding: 5px; }
}
</style>
</head>
<body>

<header class="logo-header">
    <img src="../../imgs/logo.png" alt="New Football Logo" class="logo" />
</header>

<!-- Navbar do professor -->



<main class="content">
    <section class="intro-text">
        <h2>Bem-vindo, <?= htmlspecialchars($professor_nome) ?>!</h2>
        <h1>O New Football te ajuda a organizar seus treinos e gerenciar suas turmas.</h1>
        <h2>Acompanhe seus alunos, registre presença e acompanhe o desempenho.</h2>
    </section>

    <?php if (!empty($turmas)): ?>
        <div class="turmas-container">
            <?php foreach($turmas as $t): ?>
                <div class="turma-card">
                    <h3><?= htmlspecialchars($t['nome']) ?></h3>
                    <div class="acoes">
                        <a href="gerenciar_alunos.php?turma_id=<?= $t['id'] ?>" class="alunos"><i class="bi bi-people-fill"></i> Alunos</a>
                        <a href="frequencia_professor.php?turma_id=<?= $t['id'] ?>" class="frequencia"><i class="bi bi-check2-circle"></i> Frequência</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="sem-turmas">Você ainda não possui turmas cadastradas.</p>
    <?php endif; ?>
</main>
<?php include './nav_professor.php'; ?>
</body>
</html>
