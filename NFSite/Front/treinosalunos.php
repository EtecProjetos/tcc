<?php
session_start();
include '../back/conexao.php';

// Verifica se o aluno está logado
if (!isset($_SESSION['aluno_id'])) {
    header("Location: loginAluno.php");
    exit();
}

$aluno_id = $_SESSION['aluno_id'];
$treinos = [];

// Busca a turma do aluno
$sql_turma = "SELECT turma_id FROM alunos WHERE id = ?";
$stmt_turma = $conn->prepare($sql_turma);
$stmt_turma->bind_param("i", $aluno_id);
$stmt_turma->execute();
$res_turma = $stmt_turma->get_result();

if ($res_turma && $res_turma->num_rows > 0) {
    $row_turma = $res_turma->fetch_assoc();
    $turma_id = $row_turma['turma_id'];

    // Apaga treinos antigos dessa turma
    $sql_delete = "DELETE FROM treinos WHERE data < CURDATE() AND turma_id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $turma_id);
    $stmt_delete->execute();

    // Busca os treinos da turma do aluno a partir da data atual
    $sql = "
        SELECT t.id, t.data, t.horario, tur.nome AS turma_nome, tur.dias_trino
        FROM treinos t
        JOIN turmas tur ON t.turma_id = tur.id
        WHERE t.data >= CURDATE() AND t.turma_id = ?
        ORDER BY t.data, t.horario
        LIMIT 10
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $turma_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Dias da semana em português
        $dias_semana = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];

        while ($row = $result->fetch_assoc()) {
            // Pega o índice do dia da semana e o nome correspondente
            $indice_dia = date('w', strtotime($row['data']));
            $dia_semana = strtoupper($dias_semana[$indice_dia]);

            // Prepara o array de treinos com formatações para a tela
            $treinos[] = [
                'data_formatada' => date('d/m', strtotime($row['data'])),
                'dia_semana' => $dia_semana,
                'horario' => date('H:i', strtotime($row['horario'])),
                'turma' => $row['turma_nome'],
                'dias_treino' => $row['dias_trino']
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
    <title>Treinos - New Football</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1" />
    <link rel="stylesheet" href="styles/styleTreinosAlunos.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>

<header class="logo-header">
    <img src="imgs/logo.png" alt="New Football Logo" class="logo" />
</header>

<div class="title">
    <h2>DIAS DE TREINOS</h2>
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
                </div>
            <?php endfor; ?>
        <?php endif; ?>

    <?php else: ?>
        <p class="sem-treino" role="alert">Nenhum treino encontrado</p>
    <?php endif; ?>
</div>

<div id="nav-placeholder"></div>

<script src="js/nav.js"></script>

</body>
</html>
