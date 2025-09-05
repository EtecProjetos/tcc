<?php
session_start();
include '../back/conexao.php';

// Verifica se o aluno está logado
if (!isset($_SESSION['aluno_id'])) {
    header("Location: Divulgacao_New/index.php");
    exit();
}

$aluno_id = $_SESSION['aluno_id'];

// Mês e ano selecionados (padrão: mês atual)
$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
$ano = isset($_GET['ano']) ? (int)$_GET['ano'] : (int)date('Y');

// Busca datas de treinos do aluno no mês/ano selecionados
$stmt = $conn->prepare("
    SELECT DISTINCT data FROM frequencia
    WHERE aluno_id = ? AND MONTH(data) = ? AND YEAR(data) = ?
    ORDER BY data DESC
");
$stmt->bind_param("iii", $aluno_id, $mes, $ano);
$stmt->execute();
$result = $stmt->get_result();

$datas_treino = [];
while ($row = $result->fetch_assoc()) {
    $datas_treino[] = $row['data'];
}
$stmt->close();

// Consulta presenças do aluno
$stmt = $conn->prepare("
    SELECT data, presente FROM frequencia
    WHERE aluno_id = ? AND MONTH(data) = ? AND YEAR(data) = ?
");
$stmt->bind_param("iii", $aluno_id, $mes, $ano);
$stmt->execute();
$result = $stmt->get_result();

$presencas_por_data = [];
while ($row = $result->fetch_assoc()) {
    $presencas_por_data[$row['data']] = $row['presente'];
}
$stmt->close();

// Calcula total, presentes e porcentagem
$total = count($datas_treino);
$presentes = 0;
foreach ($datas_treino as $data) {
    if (!empty($presencas_por_data[$data])) {
        $presentes++;
    }
}
$porcentagem = ($total > 0) ? round(($presentes / $total) * 100, 2) : 0;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Frequência do Aluno</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
    font-family: Arial, sans-serif;
    background: #520c6f;
    color: #fff;
    padding: 20px;
}
.container {
    max-width: 480px;
    margin: auto;
    background: #fff;
    color: #390062;
    border-radius: 20px;
    padding: 25px;
}
h2 {
    text-align: center;
    margin-bottom: 20px;
}
form {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    justify-content: space-between;
}
form select, form button {
    padding: 8px 12px;
    border-radius: 10px;
    border: 2px solid #6f2da8;
    font-weight: 600;
}
form button {
    background: #6f2da8;
    color: #fff;
    border: none;
    cursor: pointer;
}
form button:hover {
    background: #551b9a;
}
.porcentagem {
    font-size: 48px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
    color: #6f2da8;
}
.info p {
    margin: 6px 0;
    font-weight: 600;
}
.frequencia-lista {
    background: #faf7ff;
    color: #390062;
    border-radius: 12px;
    padding: 10px;
    max-height: 300px;
    overflow-y: auto;
}
.frequencia-item {
    display: flex;
    justify-content: space-between;
    padding: 10px;
    border-bottom: 1px solid #d9c9f9;
    font-weight: 600;
}
.frequencia-item i {
    font-size: 20px;
}
.presente i { color: #28a745; }
.faltou i { color: #dc3545; }
</style>
</head>
<body>
<div class="container">
    <h2>Frequência de <?= str_pad($mes,2,'0',STR_PAD_LEFT) ?>/<?= $ano ?></h2>

    <form method="GET">
        <select name="mes">
            <?php for($m=1;$m<=12;$m++): ?>
                <option value="<?= $m ?>" <?= $m==$mes?'selected':'' ?>><?= str_pad($m,2,'0',STR_PAD_LEFT) ?></option>
            <?php endfor; ?>
        </select>
        <select name="ano">
            <?php for($y = date('Y')-2; $y <= date('Y')+0; $y++): ?>
                <option value="<?= $y ?>" <?= $y==$ano?'selected':'' ?>><?= $y ?></option>
            <?php endfor; ?>
        </select>
        <button type="submit">Buscar</button>
    </form>

    <div class="porcentagem"><?= $porcentagem ?>%</div>

    <div class="info">
        <p>Total de treinos: <?= $total ?></p>
        <p>Presenças: <?= $presentes ?></p>
        <p>Faltas: <?= $total-$presentes ?></p>
    </div>

    <div class="frequencia-lista">
        <?php if($total>0): ?>
            <?php foreach($datas_treino as $data): ?>
                <div class="frequencia-item <?= (!empty($presencas_por_data[$data]) ? 'presente' : 'faltou') ?>">
                    <span><?= date('d/m/Y', strtotime($data)) ?></span>
                    <span>
                        <?php if(!empty($presencas_por_data[$data])): ?>
                            <i class="bi bi-check-circle-fill" title="Presente"></i>
                        <?php else: ?>
                            <i class="bi bi-x-circle-fill" title="Faltou"></i>
                        <?php endif; ?>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum treino encontrado para esse mês.</p>
        <?php endif; ?>
    </div>
</div>

<div id="nav-placeholder"></div>
<script src="js/nav.js"></script>
</body>
</html>
