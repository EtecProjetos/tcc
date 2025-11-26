<?php
include '../../../Back/conexao.php';
session_start();

if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: admin.php");
    exit;
}

// Mensagem de status
$mensagem = "";

// Atualizar turma do aluno
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aluno_id'])) {
    $aluno_id = intval($_POST['aluno_id']);
    $turma_id = !empty($_POST['turma_id']) ? intval($_POST['turma_id']) : null;

    $stmt = $conn->prepare("UPDATE alunos SET turma_id=? WHERE id=?");
    $stmt->bind_param("ii", $turma_id, $aluno_id);
    if ($stmt->execute()) {
        $mensagem = "✅ Turma do aluno atualizada com sucesso!";
    } else {
        $mensagem = "❌ Erro ao atualizar turma.";
    }
}

// Buscar todas as turmas
$turmas = $conn->query("SELECT id, nome FROM turmas ORDER BY nome ASC");
$turma_options = [];
while ($row = $turmas->fetch_assoc()) {
    $turma_options[$row['id']] = $row['nome'];
}

// Função para buscar alunos (usada pelo AJAX)
if (isset($_GET['ajax']) && $_GET['ajax']==1) {
    $search = $_GET['search'] ?? '';
    $filter_turma = $_GET['turma'] ?? '';

    $where = [];
    $params = [];
    $types = '';

    if ($search) {
        $where[] = "a.nome LIKE ?";
        $params[] = "%$search%";
        $types .= 's';
    }

    if ($filter_turma !== '') {
        if ($filter_turma === 'sem') {
            $where[] = "a.turma_id IS NULL";
        } else {
            $where[] = "a.turma_id = ?";
            $params[] = $filter_turma;
            $types .= 'i';
        }
    }

    $sql = "SELECT a.id, a.nome, t.nome AS turma_nome FROM alunos a LEFT JOIN turmas t ON a.turma_id = t.id";
    if ($where) $sql .= " WHERE " . implode(" AND ", $where);
    $sql .= " ORDER BY a.nome ASC";

    $stmt = $conn->prepare($sql);
    if ($params) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $alunos = [];
    while($row = $result->fetch_assoc()) $alunos[] = $row;

    echo json_encode($alunos);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gerenciar Turma do Aluno - New Football</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
<style>
* { box-sizing: border-box; }
body { margin:0; font-family:'Fredoka',sans-serif; background: linear-gradient(135deg,#000,#4c0070); color:white; display:flex; flex-direction:column; align-items:center; padding:20px; min-height:100vh;}
h1 { font-size:2.5rem; font-weight:bold; color:#FFD700; text-shadow:2px 2px 5px rgba(0,0,0,0.5); text-align:center; margin-bottom:25px;}
.card { background: linear-gradient(135deg,#7a0ea4,#a020f0); border-radius:15px; padding:20px; width:100%; max-width:900px; box-shadow:0 6px 15px rgba(0,0,0,0.5); margin-bottom:25px;}
.card h2 { color:#FFD700; margin-bottom:15px; text-align:center;}
form.search-form { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:15px; justify-content:center;}
form.search-form input, form.search-form select, form.search-form button { padding:10px; border-radius:10px; border:none; font-size:1rem; outline:none;}
form.search-form button { background:#FFD700; color:#4b0082; font-weight:bold; cursor:pointer; transition:0.3s;}
form.search-form button:hover { background:#e6c200;}
table { width:100%; border-collapse:collapse; color:#fff; text-align:center;}
th, td { padding:12px 8px; border-bottom:1px solid rgba(255,255,255,0.2);}
th { background-color: rgba(255,255,255,0.15); font-weight:600; text-transform:uppercase;}
tr:hover { background-color: rgba(255,255,255,0.1);}
select.student-turma { padding:6px; border-radius:8px; border:none; font-size:0.95rem;}
button.update-btn { padding:6px 12px; border-radius:8px; border:none; background:linear-gradient(90deg,#6a0dad,#8e2de2); color:white; font-weight:bold; cursor:pointer; transition:0.3s;}
button.update-btn:hover { background:linear-gradient(90deg,#8e2de2,#6a0dad); transform:scale(1.05);}
.mensagem { text-align:center; color:#FFD700; font-weight:bold; margin-bottom:15px; }
.voltar { text-decoration:none; color:#FFD700; font-weight:bold; margin-top:10px; display:inline-block; transition:0.3s;}
.voltar:hover { color:#fff; }
.vazio { text-align:center; font-style:italic; padding:12px; color:#ffeb99; }

@media(max-width:768px){ .card { padding:15px;} form.search-form { flex-direction:column;} input, select, button { font-size:0.95rem; padding:8px;} }
@media(max-width:480px){ h1 { font-size:2rem;} }
</style>
</head>
<body>

<h1>Gerenciar Turma do Aluno</h1>

<?php if($mensagem): ?>
<p class="mensagem"><?= $mensagem ?></p>
<?php endif; ?>

<div class="card">
<h2>Pesquisar</h2>
<form class="search-form">
    <input type="text" name="search" placeholder="Nome do Aluno" id="search-input">
    <select name="turma" id="turma-filter">
        <option value="">Todas as Turmas</option>
        <?php foreach($turma_options as $id=>$nome): ?>
            <option value="<?= $id ?>"><?= htmlspecialchars($nome) ?></option>
        <?php endforeach; ?>
        <option value="sem">Turma</option>
    </select>
</form>

<table>
    <tr>
        <th>Aluno</th>
        <th>Turma Atual</th>
        <th>Ações</th>
    </tr>
    <tbody id="tabela-alunos">
        <tr><td colspan="3" class="vazio">Carregando...</td></tr>
    </tbody>
</table>
</div>

<a href="turmas_admin.php" class="voltar">← Voltar</a>

<script>
const tabela = document.getElementById('tabela-alunos');
const inputSearch = document.getElementById('search-input');
const selectTurma = document.getElementById('turma-filter');

function carregarAlunos(){
    const search = inputSearch.value;
    const turma = selectTurma.value;

    fetch(`?ajax=1&search=${encodeURIComponent(search)}&turma=${turma}`)
    .then(res => res.json())
    .then(data => {
        tabela.innerHTML = '';
        if(data.length > 0){
            data.forEach(aluno => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                <td>${aluno.nome}</td>
                <td>${aluno.turma_nome ?? 'Sem Turma'}</td>
                <td>
                    <form method="POST" style="display:flex; gap:5px; justify-content:center;">
                        <input type="hidden" name="aluno_id" value="${aluno.id}">
                        <select name="turma_id" class="student-turma">
                            <option value="">Selecione</option>
                            <?php foreach($turma_options as $id=>$nome): ?>
                                <option value="<?= $id ?>"><?= htmlspecialchars($nome) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="update-btn">Mudar</button>
                    </form>
                </td>
                `;
                tabela.appendChild(tr);
            });
        } else {
            tabela.innerHTML = '<tr><td colspan="3" class="vazio">Nenhum aluno encontrado.</td></tr>';
        }
    });
}

inputSearch.addEventListener('input', carregarAlunos);
selectTurma.addEventListener('change', carregarAlunos);

// Carregar ao abrir a página
carregarAlunos();
</script>

</body>
</html>
