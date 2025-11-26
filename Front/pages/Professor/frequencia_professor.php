<?php
session_start();
include '../../../Back/conexao.php';

if (!isset($_SESSION['professor_id'])) {
    header("Location: loginProfessor.php");
    exit();
}

$professor_id = $_SESSION['professor_id'];

// Buscar turmas do professor
$turmas_result = $conn->prepare("SELECT id, nome FROM turmas WHERE professor_id = ?");
$turmas_result->bind_param("i", $professor_id);
$turmas_result->execute();
$turmas = $turmas_result->get_result()->fetch_all(MYSQLI_ASSOC);

// Turma selecionada
$turma_id = $_GET['turma_id'] ?? '';

$alunos = [];
if ($turma_id) {
    $query = "
        SELECT a.id, a.nome,
        COALESCE(SUM(f.presente),0) AS presente,
        COUNT(f.id) AS total_aulas
        FROM alunos a
        LEFT JOIN frequencia f ON a.id = f.aluno_id
        WHERE a.turma_id = ?
        GROUP BY a.id, a.nome
        ORDER BY a.nome ASC
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $turma_id);
    $stmt->execute();
    $alunos = $stmt->get_result();
}
?>
<?php include './nav_professor.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Frequência</title>
<link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">
<style>
body {
    margin:0;
    padding:0;
    font-family:'Fredoka', sans-serif;
    background: linear-gradient(to bottom, #6a0dad, #000);
    color:#fff;
    min-height:100vh;
    display:flex;
    flex-direction:column;
    align-items:center;
    padding:30px 20px;
}
h1 {text-align:center; color:#FFD700; margin-bottom:20px;}
form {margin-bottom:20px; display:flex; flex-wrap:wrap; gap:10px; justify-content:center;}
select, button {padding:10px 15px; border-radius:10px; border:none; font-size:1rem;}
button {background:#FFD700; color:#4b0082; font-weight:bold; cursor:pointer;}
button:hover {background:#e6c200;}
table {width:100%; max-width:700px; border-collapse:collapse; margin-top:10px;}
th, td {padding:12px; text-align:center; border-bottom:1px solid rgba(255,255,255,0.2);}
th {background: rgba(255,255,255,0.15);}
tr:hover {background: rgba(255,255,255,0.1);}
@media(max-width:480px){
    table, thead, tbody, th, td, tr {display:block;}
    thead tr {display:none;}
    tbody tr {margin-bottom:15px; padding:15px; border-radius:16px; background: rgba(255,255,255,0.9); color:#4b0082;}
    tbody td {padding-left:120px; position:relative; text-align:left;}
    tbody td::before {position:absolute; left:20px; top:50%; transform:translateY(-50%); font-weight:700; content:attr(data-label);}
}
.sem-alunos {text-align:center; margin-top:20px; color:#ffeb99;}
</style>
</head>
<body>

<h1>Frequência da Turma</h1>

<form method="GET" id="formTurma">
    <select name="turma_id" required onchange="document.getElementById('formTurma').submit()">
        <option value="">Selecione a turma</option>
        <?php foreach($turmas as $t): ?>
            <option value="<?= $t['id'] ?>" <?= ($turma_id==$t['id'])?'selected':'' ?>><?= htmlspecialchars($t['nome']) ?></option>
        <?php endforeach; ?>
    </select>
</form>

<?php if ($turma_id && $alunos->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Aluno</th>
                <th>Frequência</th>
            </tr>
        </thead>
        <tbody>
        <?php while($a = $alunos->fetch_assoc()): 
            $frequencia = ($a['total_aulas'] > 0) ? round(($a['presente']/$a['total_aulas'])*100,1) : 0;
        ?>
            <tr>
                <td data-label="Aluno"><?= htmlspecialchars($a['nome']) ?></td>
                <td data-label="Frequência"><?= $frequencia ?>%</td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php elseif($turma_id): ?>
    <p class="sem-alunos">Nenhum aluno cadastrado nesta turma.</p>
<?php endif; ?>

<script src="../../js/nav_professor.js"></script>
</body>
</html>
