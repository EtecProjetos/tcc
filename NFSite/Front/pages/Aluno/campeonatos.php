<?php
session_start();
include '../back/conexao.php';

// ID do aluno logado
$aluno_id = $_SESSION['aluno_id'] ?? 0;
if (!$aluno_id) {
    echo "Aluno não logado.";
    exit;
}

// Busca a turma do aluno
$sql_turma = "SELECT turma_id FROM alunos WHERE id = ?";
$stmt = $conn->prepare($sql_turma);
$stmt->bind_param("i", $aluno_id);
$stmt->execute();
$result = $stmt->get_result();
$turma = $result->fetch_assoc();
$turma_id = $turma['turma_id'] ?? 0;

// Busca os campeonatos da turma
$sql = "SELECT c.*, p.nome AS tecnico_nome 
        FROM campeonatos c
        JOIN professores p ON c.professor_id = p.id
        WHERE c.turma_id = ?
        ORDER BY c.data DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $turma_id);
$stmt->execute();
$campeonatos = $stmt->get_result();


?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Campeonatos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
            padding-bottom: 80px;
        }
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .card-title {
            font-size: 1.3rem;
            font-weight: bold;
        }
        .info {
            font-size: 0.95rem;
            color: #555;
        }
        .convocado {
            margin-top: 10px;
            font-weight: bold;
            color: #198754;
        }
        .nao-convocado {
            margin-top: 10px;
            font-weight: bold;
            color: #dc3545;
        }
        .footer-icons {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #fff;
            border-top: 1px solid #ccc;
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
            z-index: 10;
        }
        .footer-icons a {
            color: #555;
            text-decoration: none;
            font-size: 1.4rem;
        }
        .footer-icons a:hover {
            color: #6f42c1;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <h3 class="mb-4">Campeonatos</h3>

    <?php while ($camp = $campeonatos->fetch_assoc()): ?>

        <?php
        // Verifica se o aluno foi convocado
        $sql_conv = "SELECT 1 FROM convocados_campeonato WHERE campeonato_id = ? AND aluno_id = ?";
        $stmt_conv = $conn->prepare($sql_conv);
        $stmt_conv->bind_param("ii", $camp['id'], $aluno_id);
        $stmt_conv->execute();
        $convocado = $stmt_conv->get_result()->num_rows > 0;
        ?>

        <div class="card p-3">
            <div class="card-title"><?= htmlspecialchars($camp['nome']) ?></div>
            <div class="info">Categoria: <?= htmlspecialchars($camp['categoria']) ?> anos</div>
            <div class="info">Técnico: <?= htmlspecialchars($camp['tecnico_nome']) ?></div>
            <?php if ($convocado): ?>
                <div class="convocado"><i class="bi bi-check-circle-fill"></i> Você foi convocado!</div>
            <?php endif; ?>
        </div>

    <?php endwhile; ?>
</div>

<!-- Rodapé fixo com ícones -->
<div class="footer-icons">
    <a href="home.php"><i class="bi bi-house-fill"></i></a>
    <a href="treinos.php"><i class="bi bi-calendar-check-fill"></i></a>
    <a href="jogos.php"><i class="bi bi-trophy-fill"></i></a>
    <a href="perfil.php"><i class="bi bi-person-fill"></i></a>
</div>

</body>
</html>
