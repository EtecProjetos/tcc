<?php
include '../back/conexao.php';
session_start();

$turma_id = $_GET['turma_id'] ?? null;
$data = $_GET['data'] ?? null;

if (!$turma_id || !$data) {
    echo "Turma e data obrigatórios.";
    exit;
}

// Busca alunos da turma
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
<title>Chamada</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="shortcut icon" href="imgs/logo.png" type="image/x-icon">
<style>
body {
    margin: 0;
    font-family: 'Roboto', sans-serif;
    background: linear-gradient(to bottom, #6a0dad 0%, #000000 100%);
    color: #4b0082;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px 10px;
}

.container {
    background: #fff;
    border-radius: 16px;
    max-width: 700px;
    width: 100%;
    padding: 30px 35px;
    box-shadow: 0 4px 20px rgba(111, 45, 168, 0.3);
    color: #4b0082;
    box-sizing: border-box;
}

h2 {
    text-align: center;
    font-weight: 700;
    margin-bottom: 20px;
    color: #6f2da8;
}

form {
    display: flex;
    flex-direction: column;
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

th {
    color: #390062;
}

tr:nth-child(even) { background: #f2f2f2; }

.presenca-checkbox {
    width: 18px;
    height: 18px;
}

.btn, .btn-voltar {
    padding: 14px 0;
    font-size: 1.2rem;
    font-weight: 700;
    border-radius: 25px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
    width: 100%;
    margin-top: 25px;
    text-align: center;
}

.btn {
    background-color: #ffd700;
    color: #4b0082;
}

.btn:hover {
    background-color: #ffe345;
}

.btn-voltar {
    color: #4b0082;
    text-decoration: none;
}

.btn-voltar:hover {
    color: #000000;
}

.summary {
    margin-top: 15px;
    font-weight: 600;
    font-size: 16px;
    color: #390062;
    text-align: center;
}

@media (max-width: 480px) {
    .container { padding: 20px; }
    h2 { font-size: 1.6rem; }
    table th, table td { font-size: 14px; }
    .btn, .btn-voltar { font-size: 14px; padding: 12px 0; }
}
</style>
</head>
<body>
<div class="container">
    <a href="#" onclick="window.history.back();" class="btn-voltar">← Voltar</a>
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
    checkboxes.forEach(cb => { if(cb.checked) presentes++; });
    summary.textContent = `Presentes: ${presentes} | Ausentes: ${checkboxes.length - presentes}`;
}

checkboxes.forEach(cb => cb.addEventListener('change', atualizarResumo));
atualizarResumo();
</script>
</body>
</html>
