<?php
include '../back/conexao.php';
session_start();



// Busca turma do aluno (sem created_at)
$stmt = $conn->prepare("SELECT turma_id FROM alunos WHERE id = ?");
$stmt->bind_param("i", $aluno_id);
$stmt->execute();
$stmt->bind_result($turma_id);
$stmt->fetch();
$stmt->close();

$ano_atual = (int)date('Y');
// Define ano de criação padrão como 3 anos atrás
$ano_criacao = $ano_atual - 3;

$ano_inicio = max($ano_criacao, $ano_atual - 2);
$ano_fim = $ano_atual + 1;

// Mês e ano selecionados (padrão mês atual)
$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : (int)date('m');
$ano = isset($_GET['ano']) ? (int)$_GET['ano'] : $ano_atual;

// Ajusta ano selecionado caso seja menor que o ano mínimo permitido
if ($ano < $ano_inicio) {
    $ano = $ano_inicio;
}

// Busca treinos da turma para o mês/ano selecionados
$stmt = $conn->prepare("
    SELECT DISTINCT data FROM frequencias 
    WHERE turma_id = ? AND MONTH(data) = ? AND YEAR(data) = ?
    ORDER BY data DESC
");
$stmt->bind_param("iii", $turma_id, $mes, $ano);
$stmt->execute();
$result = $stmt->get_result();

$datas_treino = [];
while ($row = $result->fetch_assoc()) {
    $datas_treino[] = $row['data'];
}
$stmt->close();

// Consulta presenças do aluno no período
$stmt = $conn->prepare("
    SELECT data, presente FROM frequencias 
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

$total = count($datas_treino);
$presentes = 0;
foreach ($datas_treino as $data) {
    if (isset($presencas_por_data[$data]) && $presencas_por_data[$data]) {
        $presentes++;
    }
}
$porcentagem = ($total > 0) ? round(($presentes / $total) * 100, 2) : 0;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Histórico de Frequência</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background: #390062;
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: #fff;
            color: #390062;
            max-width: 480px;
            width: 100%;
            border-radius: 20px;
            padding: 30px 35px;
            box-shadow: 0 8px 20px rgba(111, 45, 168, 0.35);
            text-align: center;
        }
        h2 {
            margin-bottom: 25px;
            font-weight: 700;
            font-size: 1.9rem;
        }

        /* Formulário */
        form {
            display: flex;
            gap: 15px;
            justify-content: space-between;
            flex-wrap: nowrap;
            margin-bottom: 30px;
            align-items: flex-end;
        }
        form > div {
            flex: 1 1 auto;
            min-width: 110px;
        }
        form > div:last-child {
            flex: 0 0 auto;
            min-width: 120px;
        }
        label {
            font-weight: 600;
            color: #6f2da8;
            margin-bottom: 6px;
            display: block;
            text-align: left;
        }
        select {
            padding: 8px 12px;
            border-radius: 10px;
            border: 2px solid #6f2da8;
            background: #faf7ff;
            color: #390062;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }
        select:focus {
            outline: none;
            border-color: #390062;
        }
        button {
            background: #6f2da8;
            border: none;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 20px;
            padding: 10px 22px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(111, 45, 168, 0.5);
            transition: background-color 0.3s ease;
            width: 100%;
            max-width: 120px;
        }
        button:hover {
            background: #551b9a;
        }

        .porcentagem {
            font-size: 52px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #6f2da8;
        }
        .info p {
            margin: 6px 0;
            font-weight: 600;
            font-size: 1.1rem;
            color: #4b0082;
        }
        .frequencia-lista {
            margin-top: 30px;
            text-align: left;
            max-height: 300px;
            overflow-y: auto;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(111, 45, 168, 0.15);
            background: #faf7ff;
            padding: 10px 15px;
            color: #390062;
        }
        .frequencia-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 15px;
            border-bottom: 1px solid #d9c9f9;
            font-weight: 600;
            font-size: 1rem;
            align-items: center;
        }
        .frequencia-item:last-child {
            border-bottom: none;
        }
        .frequencia-item i {
            font-size: 24px;
        }
        .presente i {
            color: #28a745;
        }
        .faltou i {
            color: #dc3545;
        }
        /* Scrollbar para Webkit */
        .frequencia-lista::-webkit-scrollbar {
            width: 8px;
        }
        .frequencia-lista::-webkit-scrollbar-thumb {
            background: #6f2da8;
            border-radius: 8px;
        }
        .frequencia-lista::-webkit-scrollbar-track {
            background: #e9dfff;
            border-radius: 8px;
        }
        @media (max-width: 480px) {
            .container {
                padding: 25px 20px;
                max-width: 100%;
            }
            form {
                flex-wrap: wrap;
                flex-direction: column;
                align-items: stretch;
            }
            form > div {
                min-width: auto;
                flex: none;
                width: 100%;
            }
            button {
                max-width: 100%;
                margin-top: 10px;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Frequência de <?= sprintf('%02d', $mes) ?>/<?= $ano ?></h2>

        <form method="GET" aria-label="Selecionar mês e ano da frequência">
            <div>
                <label for="mes">Mês</label>
                <select name="mes" id="mes" required>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= $m === $mes ? 'selected' : '' ?>>
                            <?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <label for="ano">Ano</label>
                <select name="ano" id="ano" required>
                    <?php
                    for ($y = $ano_inicio; $y <= $ano_fim; $y++):
                        $selected = ($y === $ano) ? 'selected' : '';
                    ?>
                        <option value="<?= $y ?>" <?= $selected ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <button type="submit" aria-label="Buscar frequência para o mês e ano selecionados">Buscar</button>
            </div>
        </form>

        <div class="porcentagem" aria-live="polite"><?= $porcentagem ?>%</div>

        <div class="info" role="region" aria-label="Resumo da frequência mensal">
            <p><strong>Total de treinos:</strong> <?= $total ?></p>
            <p><strong>Presenças:</strong> <?= $presentes ?></p>
            <p><strong>Faltas:</strong> <?= $total - $presentes ?></p>
        </div>

        <div class="frequencia-lista" role="list" aria-label="Lista de presenças e faltas por treino">
            <?php if ($total > 0): ?>
                <?php foreach ($datas_treino as $data): ?>
                    <div class="frequencia-item <?= (isset($presencas_por_data[$data]) && $presencas_por_data[$data]) ? 'presente' : 'faltou' ?>" role="listitem">
                        <span><?= date('d/m/Y', strtotime($data)) ?></span>
                        <span>
                            <?php if (isset($presencas_por_data[$data]) && $presencas_por_data[$data]): ?>
                                <i class="bi bi-check-circle-fill" title="Presente" aria-label="Presente"></i>
                            <?php else: ?>
                                <i class="bi bi-x-circle-fill" title="Faltou" aria-label="Faltou"></i>
                            <?php endif; ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum treino encontrado para esse mês.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Espaço para a navbar -->
    <div id="nav-placeholder"></div>
    <script src="js/nav.js"></script>
</body>
</html>
