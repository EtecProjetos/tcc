<?php
include '../back/conexao.php';
session_start();

$turma_id = $_GET['turma_id'] ?? null;
$data = $_GET['data'] ?? null;

if (!$turma_id || !$data) {
    echo "Turma e data obrigatórios.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM alunos WHERE turma_id = ? ORDER BY nome ASC");
$stmt->bind_param("i", $turma_id);
$stmt->execute();
$result = $stmt->get_result();
$alunos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Registrar Presença</title>
    <style>
        body {
            background-color: #390062;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            color: #fff;
        }
        .container {
            background: #fff;
            max-width: 700px;
            margin: 30px auto;
            padding: 20px;
            border-radius: 16px;
            color: #333;
        }
        h2 {
            text-align: center;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        .btn {
            margin-top: 20px;
            background-color: #390062;
            color: white;
            padding: 10px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #5a008a;
        }
        .summary {
            margin-top: 15px;
            font-weight: 600;
            font-size: 16px;
            color: #390062;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Chamada - <?= date('d/m/Y', strtotime($data)) ?></h2>
    <form action="salvar_chamada.php" method="POST" id="formChamada">
        <input type="hidden" name="turma_id" value="<?= $turma_id ?>">
        <input type="hidden" name="data" value="<?= $data ?>">

        <table>
            <thead>
                <tr>
                    <th>Aluno</th>
                    <th style="text-align:center;">Presente</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alunos as $aluno): ?>
                    <tr>
                        <td><?= htmlspecialchars($aluno['nome']) ?></td>
                        <td style="text-align:center;">
                            <input type="checkbox" class="presenca-checkbox" name="presencas[<?= $aluno['id'] ?>]" value="1" checked>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="summary" id="summary">
            Presentes: <?= count($alunos) ?> | Ausentes: 0
        </div>

        <button class="btn" type="submit">Salvar Chamada</button>
    </form>
</div>

<script>
    const checkboxes = document.querySelectorAll('.presenca-checkbox');
    const summary = document.getElementById('summary');

    function atualizarResumo() {
        let presentes = 0;
        checkboxes.forEach(cb => {
            if (cb.checked) presentes++;
        });
        const ausentes = checkboxes.length - presentes;
        summary.textContent = `Presentes: ${presentes} | Ausentes: ${ausentes}`;
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', atualizarResumo);
    });

    // Atualiza o resumo na carga da página (caso mude algo por JS)
    atualizarResumo();
</script>

</body>
</html>
