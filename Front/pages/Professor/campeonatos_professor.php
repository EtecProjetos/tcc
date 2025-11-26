<?php
session_start();
include '../../../Back/conexao.php';

if (!isset($_SESSION['professor_id'])) {
    header("Location: loginProfessor.php");
    exit();
}

$professor_id = $_SESSION['professor_id'];
$convocados = [];

// Buscar campeonatos que o professor está convocado ou que tem alunos convocados
$sql = "
    SELECT c.campeonato_id, ca.nome AS campeonato_nome,
           a.nome AS aluno_nome, p.nome AS professor_nome
    FROM convocacoes c
    LEFT JOIN campeonatos ca ON c.campeonato_id = ca.id
    LEFT JOIN alunos a ON c.aluno_id = a.id
    LEFT JOIN professores p ON c.professor_id = p.id
    WHERE c.professor_id = ? OR c.aluno_id IS NOT NULL
    ORDER BY ca.nome ASC, a.nome ASC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $professor_id);
$stmt->execute();
$res = $stmt->get_result();

// Agrupar por campeonato
while ($row = $res->fetch_assoc()) {
    $camp_id = $row['campeonato_id'];
    if (!isset($convocados[$camp_id])) {
        $convocados[$camp_id] = [
            'nome' => $row['campeonato_nome'],
            'alunos' => [],
            'professores' => []
        ];
    }
    if (!empty($row['aluno_nome'])) $convocados[$camp_id]['alunos'][] = $row['aluno_nome'];
    if (!empty($row['professor_nome'])) $convocados[$camp_id]['professores'][] = $row['professor_nome'];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Convocações - Professor</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #000000, #4c0070);
    color: white;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
}
.page-wrapper {max-width:800px; margin:0 auto;}
.card {background: rgba(255,255,255,0.1); border-radius:12px; padding:20px; margin-bottom:15px;}
.card .titulo {font-weight:bold; font-size:1.2rem; color:#FFD700; margin-bottom:10px;}
.card .info {font-size:1rem; margin-bottom:5px;}
.card .subtitulo {font-weight:bold; color:#FFD700; margin-top:5px;}
.sem-convocacao {color:#FFD700; font-weight:bold; text-align:center; margin-top:20px;}
.btn-alunos {background:#FFD700; color:#4c0070; border-radius:8px; font-weight:bold; font-size:0.9rem;}
.btn-alunos:hover {background:#e6c200;}
.modal-body {color:#000;}
</style>
</head>
<body>
<div class="page-wrapper">
    <h2 class="text-center mb-4" style="color:#FFD700;">Convocações</h2>

    <?php if(count($convocados) > 0): ?>
        <?php foreach($convocados as $camp_id => $c): ?>
            <div class="card">
                <div class="titulo"><?= htmlspecialchars($c['nome']) ?></div>

                <?php if(count($c['professores'])>0): ?>
                    <div class="subtitulo">Professores convocados:</div>
                    <div class="info"><?= implode(', ', $c['professores']) ?></div>
                <?php endif; ?>

                <?php if(count($c['alunos'])>0): ?>
                    <button class="btn btn-alunos mt-2" data-bs-toggle="modal" data-bs-target="#modalAlunos<?= $camp_id ?>">
                        Ver Alunos Convocados (<?= count($c['alunos']) ?>)
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="modalAlunos<?= $camp_id ?>" tabindex="-1" aria-labelledby="modalLabel<?= $camp_id ?>" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel<?= $camp_id ?>">Alunos Convocados - <?= htmlspecialchars($c['nome']) ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                          </div>
                          <div class="modal-body">
                            <ul>
                                <?php foreach($c['alunos'] as $aluno): ?>
                                    <li><?= htmlspecialchars($aluno) ?></li>
                                <?php endforeach; ?>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="sem-convocacao">Nenhuma convocação encontrada.</p>
    <?php endif; ?>

</div>

    <div id="nav_professor-placeholder"></div>
    <script src="../../js/nav.js"></script>
    <?php include './nav_professor.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
