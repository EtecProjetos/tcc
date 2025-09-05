<?php
session_start();
include '../back/conexao.php';

// Redireciona se o professor não estiver logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: loginProfessor.php");
    exit();
}

$professor_id = $_SESSION['professor_id'];

// Deleta jogos antigos automaticamente
$conn->query("DELETE FROM jogos WHERE data < CURDATE()");

// Inserir novo jogo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data'], $_POST['horario'], $_POST['categoria'], $_POST['local'], $_POST['adversario'], $_POST['logo_url'], $_POST['turma_id'])) {
    $data = $_POST['data'];
    $horario = $_POST['horario'];
    $categoria = strtoupper($_POST['categoria']);
    $local = strtoupper($_POST['local']);
    $adversario = strtoupper($_POST['adversario']);
    $logo_url = $_POST['logo_url'];
    $turma_id = $_POST['turma_id'];

    $stmt = $conn->prepare("INSERT INTO jogos (data, horario, categoria, local, adversario, logo_url, professor_id, turma_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssii", $data, $horario, $categoria, $local, $adversario, $logo_url, $professor_id, $turma_id);
    $stmt->execute();
    $stmt->close();

    header("Location: jogosprofessor.php");
    exit();
}

// Excluir jogo
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $conn->query("DELETE FROM jogos WHERE id = $id AND professor_id = $professor_id");
    header("Location: jogosprofessor.php");
    exit();
}

// Buscar turmas do professor
$turmas_result = $conn->query("SELECT id, nome FROM turmas WHERE professor_id = $professor_id");

// Buscar jogos do professor
$sql = "
SELECT j.id, j.data, j.horario, j.categoria, j.local, j.adversario, j.logo_url, t.nome AS turma_nome
FROM jogos j
JOIN turmas t ON j.turma_id = t.id
WHERE j.professor_id = $professor_id
ORDER BY j.data DESC
";
$jogos = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Gerenciar Jogos</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />

<style>
/* --- ESTILO DO SEU SISTEMA --- */
body { margin:0; background-color:#520c6f; font-family:'Roboto',Arial,sans-serif; color:#4b0082; min-height:100vh; display:flex; flex-direction:column; align-items:center; padding:20px 10px; }
h1 { font-weight:700; font-size:2rem; margin-bottom:30px; color:#6f2da8; text-align:center; }
.container { background:#fff; border-radius:16px; max-width:900px; width:100%; padding:30px 35px; box-shadow:0 4px 20px rgba(111,45,168,0.3); color:#4b0082; box-sizing:border-box; }
form { display:flex; flex-wrap:wrap; gap:20px; margin-bottom:40px; justify-content:space-between; }
form label { flex:1 0 100%; font-weight:500; margin-bottom:8px; }
form input[type="date"], form input[type="time"], form input[type="text"], form select { flex:1 1 calc(33% - 15px); padding:14px 16px; font-size:16px; border:2px solid #6f2da8; border-radius:14px; color:#4b0082; box-sizing:border-box; }
form input:focus, form select:focus { border-color:#390062; outline:none; }
form button { flex:1 0 100%; padding:18px 0; font-size:1.2rem; font-weight:700; background-color:#6f2da8; color:white; border:none; border-radius:20px; cursor:pointer; box-shadow:0 4px 15px rgba(111,45,168,0.5); }
form button:hover { background-color:#551b9a; }
.table-wrapper { overflow-x:auto; -webkit-overflow-scrolling:touch; box-shadow:0 4px 20px rgba(111,45,168,0.3); border-radius:16px; background:#fff; padding:15px 20px; }
.table-wrapper::-webkit-scrollbar { height:8px; }
.table-wrapper::-webkit-scrollbar-thumb { background:#6f2da8; border-radius:10px; }
.table-wrapper::-webkit-scrollbar-track { background:#eee; border-radius:10px; }
table { width:100%; border-collapse:separate; border-spacing:0 14px; min-width:800px; }
thead { background-color:#6f2da8; color:white; font-weight:700; position:sticky; top:0; }
th, td { padding:16px 14px; text-align:center; background-color:#fdf7ff; color:#4b0082; font-weight:600; box-shadow:0 3px 8px rgba(111,45,168,0.12); }
img.logo { height:40px; border-radius:5px; object-fit:contain; max-width:100%; }
td:last-child { display:flex; justify-content:center; gap:12px; flex-wrap:nowrap; overflow-x:auto; }
a.btn { display:inline-flex; align-items:center; gap:6px; padding:8px 14px; border-radius:12px; font-weight:700; font-size:14px; color:white; text-decoration:none; min-width:90px; justify-content:center; }
a.btn-edit { background:#2980b9; }
a.btn-edit:hover { background:#1c5980; }
a.btn-delete { background:#e74c3c; }
a.btn-delete:hover { background:#b8362a; }
.empty { text-align:center; color:#aaa; font-size:1.3rem; padding:40px 0; }
@media(max-width:720px){ form input, form select { flex:1 0 100%; } th, td { font-size:14px; padding:12px 8px; } a.btn { font-size:13px; padding:7px 12px; } }
@media(max-width:480px){ table, thead, tbody, th, td, tr { display:block; } thead tr { display:none; } tbody tr { margin-bottom:20px; background:#fdf7ff; border-radius:16px; padding:15px 20px; box-shadow:0 3px 12px rgba(111,45,168,0.15); } tbody td { display:flex; justify-content:space-between; padding:10px 0; border:none; box-shadow:none; } tbody td::before { content:attr(data-label); font-weight:700; color:#6f2da8; flex:1 0 40%; } }
</style>
</head>
<body>
<div class="container">
<h1>Adicionar Novo Jogo</h1>
<form method="POST">
    <label for="data">Data:</label>
    <input type="date" id="data" name="data" required>

    <label for="horario">Horário:</label>
    <input type="time" id="horario" name="horario" required>

    <label for="categoria">Categoria:</label>
    <input type="text" id="categoria" name="categoria" required>

    <label for="local">Local:</label>
    <input type="text" id="local" name="local" required>

    <label for="adversario">Adversário:</label>
    <input type="text" id="adversario" name="adversario" required>

    <label for="logo_url">URL da Logo do Adversário:</label>
    <input type="text" id="logo_url" name="logo_url" required>

    <label for="turma_id">Turma:</label>
    <select id="turma_id" name="turma_id" required>
        <option value="">Selecione a turma</option>
        <?php while ($turma = $turmas_result->fetch_assoc()): ?>
            <option value="<?= $turma['id'] ?>"><?= htmlspecialchars($turma['nome']) ?></option>
        <?php endwhile; ?>
    </select>

    <button type="submit">Adicionar Jogo</button>
</form>

<h2 style="text-align:center; color:#6f2da8;">Lista de Jogos Cadastrados</h2>

<?php if ($jogos->num_rows > 0): ?>
<div class="table-wrapper">
<table>
<thead>
<tr>
    <th>Data</th>
    <th>Horário</th>
    <th>Categoria</th>
    <th>Local</th>
    <th>Adversário</th>
    <th>Logo</th>
    <th>Turma</th>
    <th>Ações</th>
</tr>
</thead>
<tbody>
<?php while ($j = $jogos->fetch_assoc()): ?>
<tr>
    <td data-label="Data"><?= date('d/m/Y', strtotime($j['data'])) ?></td>
    <td data-label="Horário"><?= date('H:i', strtotime($j['horario'])) ?></td>
    <td data-label="Categoria"><?= strtoupper($j['categoria']) ?></td>
    <td data-label="Local"><?= strtoupper($j['local']) ?></td>
    <td data-label="Adversário"><?= strtoupper($j['adversario']) ?></td>
    <td data-label="Logo"><img src="<?= htmlspecialchars($j['logo_url']) ?>" class="logo" alt="Logo do adversário"></td>
    <td data-label="Turma"><?= strtoupper($j['turma_nome']) ?></td>
    <td data-label="Ações">
        <a href="editar_jogo.php?id=<?= $j['id'] ?>" class="btn btn-edit"><i class="bi bi-pencil-square"></i> Editar</a>
        <a href="?excluir=<?= $j['id'] ?>" class="btn btn-delete" onclick="return confirm('Deseja excluir este jogo?')"><i class="bi bi-trash"></i> Excluir</a>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
<?php else: ?>
<p class="empty">Nenhum jogo cadastrado.</p>
<?php endif; ?>
</div>

  <div id="nav-placeholder"></div>

  <!-- Scripts -->
  <script src="js/nav_professor.js"></script>
</body>
</html>
