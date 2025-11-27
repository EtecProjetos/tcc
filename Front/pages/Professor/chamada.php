<?php
include '../../../Back/conexao.php';
session_start();

$turma_id = $_GET['turma_id'] ?? null;
$data = $_GET['data'] ?? null;

if (!$turma_id || !$data) {
    die("Turma e data obrigatórios.");
}

// Busca alunos da turma
$stmt = $conn->prepare("SELECT id, nome FROM alunos WHERE turma_id=? ORDER BY nome ASC");
$stmt->bind_param("i", $turma_id);
$stmt->execute();
$result = $stmt->get_result();
$alunos = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Chamada</title>

<link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">
<style>
body { font-family: 'Roboto', sans-serif; background: linear-gradient(to bottom, #6a0dad 0%, #000000 100%); margin:0; display:flex; justify-content:center; align-items:flex-start; min-height:100vh; color:#4b0082; padding:20px; }
.container { background:#fff; border-radius:16px; max-width:700px; width:100%; padding:30px 35px; box-shadow:0 4px 20px rgba(111,45,168,0.3); }
h2 { text-align:center; color:#6f2da8; margin-bottom:20px; }
table { width:100%; border-collapse:collapse; }
th, td { padding:12px; text-align:left; }
th { color:#390062; }
tr:nth-child(even){ background:#f2f2f2; }
.presenca-checkbox { width:18px; height:18px; }
button { margin-top:20px; width:100%; padding:14px 0; border:none; border-radius:25px; background:#ffd700; color:#4b0082; font-weight:700; cursor:pointer; font-size:1.1rem; }
button:hover { background:#ffe345; }
.summary { margin-top:15px; font-weight:600; text-align:center; color:#390062; }
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
            <tr><th>Aluno</th><th>Presente</th></tr>
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

    <div class="summary" id="summary">Presentes: <?= count($alunos) ?> | Ausentes: 0</div>
    <button type="submit">Salvar Chamada</button>
</form>
</div>

<script>
const checkboxes = document.querySelectorAll('.presenca-checkbox');
const summary = document.getElementById('summary');

function atualizarResumo() {
    let presentes = 0;
    checkboxes.forEach(cb => { if(cb.checked) presentes++; });
    summary.textContent = `Presentes: ${presentes} | Ausentes: ${checkboxes.length - presentes}`;
}

checkboxes.forEach(cb => cb.addEventListener('change', atualizarResumo));
</script>
</body>
</html>
