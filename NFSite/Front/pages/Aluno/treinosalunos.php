<?php
session_start();
include '../../../Back/conexao.php';

// Verifica se o aluno está logado
if (!isset($_SESSION['aluno_id'])) {
    header("Location: loginAluno.php");
    exit();
}

$aluno_id = $_SESSION['aluno_id'];
$treinos = [];

// Busca a turma do aluno e nome da turma
$sql_turma = "
    SELECT a.turma_id, t.nome AS turma_nome
    FROM alunos a
    LEFT JOIN turmas t ON a.turma_id = t.id
    WHERE a.pessoa = ?
";
$stmt_turma = $conn->prepare($sql_turma);
$stmt_turma->bind_param("i", $aluno_id);
$stmt_turma->execute();
$res_turma = $stmt_turma->get_result();

$turma_id = null;
$turma_nome = 'Sem turma';

if ($res_turma && $res_turma->num_rows > 0) {
    $row_turma = $res_turma->fetch_assoc();
    $turma_id = $row_turma['turma_id'];
    if ($row_turma['turma_nome']) $turma_nome = $row_turma['turma_nome'];
}

// Se houver turma, busca treinos futuros
if ($turma_id) {
    $sql = "
        SELECT t.id, t.data, t.horario
        FROM treinos t
        WHERE t.data >= CURDATE() AND t.turma_id = ?
        ORDER BY t.data, t.horario
        LIMIT 10
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $turma_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $dias_semana = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];

        while ($row = $result->fetch_assoc()) {
            $indice_dia = date('w', strtotime($row['data']));
            $dia_semana = strtoupper($dias_semana[$indice_dia]);

            $treinos[] = [
                'data_formatada' => date('d/m', strtotime($row['data'])),
                'dia_semana' => $dia_semana,
                'horario' => date('H:i', strtotime($row['horario']))
            ];
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Treinos - <?= htmlspecialchars($turma_nome) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../../styles/styleTreinosAlunos.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">
</head>
<body>

<header class="logo-header">
    <img src="../../imgs/logo.png" alt="New Football Logo" class="logo" />
</header>

<div class="title">
    <h2>DIAS DE TREINOS - <?= htmlspecialchars($turma_nome) ?></h2>
</div>

<div id="treinos-container">
    <?php if (count($treinos) > 0): ?>
        <!-- Primeiro treino em destaque -->
        <div class="treino-box destaque" role="region" aria-label="Treino principal">
            <div class="icon-calendario" aria-hidden="true">
                <i class="bi bi-calendar-event"></i>
            </div>
            <div class="data"><?= htmlspecialchars($treinos[0]['data_formatada']) ?></div>
            <div class="dia"><?= htmlspecialchars($treinos[0]['dia_semana']) ?></div>
            <p><span id="horario-destaque"><?= htmlspecialchars($treinos[0]['horario']) ?></span></p>
        </div>

        <!-- Próximos treinos -->
        <?php if (count($treinos) > 1): ?>
            <div class="proximos-label">PRÓXIMOS TREINOS</div>
            <?php for ($i = 1; $i < count($treinos); $i++): ?>
                <div class="treino-box" role="region" aria-label="Treino adicional">
                    <div class="icon-calendario" aria-hidden="true">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div class="data"><?= htmlspecialchars($treinos[$i]['data_formatada']) ?></div>
                    <div class="dia"><?= htmlspecialchars($treinos[$i]['dia_semana']) ?></div>
                    <p><span id="horario-destaque"><?= htmlspecialchars($treinos[$i]['horario']) ?></span></p>
                </div>
            <?php endfor; ?>
        <?php endif; ?>

    <?php else: ?>
        <p class="sem-treino" role="alert">Nenhum treino encontrado</p>
    <?php endif; ?>
</div>

<div id="nav-placeholder"></div>

<script src="../../js/nav.js"></script>
</body>
</html>
