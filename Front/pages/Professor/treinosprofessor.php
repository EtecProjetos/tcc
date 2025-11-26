<?php
session_start();

// Caminho absoluto para a raiz do projeto
define('ROOT_PATH', __DIR__ . '/../../../');

// Inclui a conexão com o banco
include ROOT_PATH . 'Back/conexao.php';

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: loginProfessor.php");
    exit();
}

$professor_id = $_SESSION['professor_id'];

// =====================
// EXCLUI AUTOMATICAMENTE TREINOS COM DATA PASSADA
// =====================
$hoje = date('Y-m-d');
$conn->query("DELETE FROM treinos WHERE professor_id = $professor_id AND data < '$hoje'");

// Inserção de novo treino
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data'], $_POST['horario'], $_POST['turma_id'])) {
    $data = $_POST['data'];
    $horario = $_POST['horario'];
    $turma_id = intval($_POST['turma_id']);

    $stmt = $conn->prepare("INSERT INTO treinos (data, horario, turma_id, professor_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $data, $horario, $turma_id, $professor_id);
    $stmt->execute();
    $stmt->close();

    header("Location: treinosprofessor.php");
    exit();
}

// Exclusão manual de treino
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    $conn->query("DELETE FROM treinos WHERE id = $id AND professor_id = $professor_id");
    header("Location: treinosprofessor.php");
    exit();
}

// Buscar turmas do professor logado
$t_result = $conn->query("
    SELECT t.id, t.nome
    FROM turmas t
    INNER JOIN turma_professor tp ON tp.turma_id = t.id
    WHERE tp.professor_id = $professor_id
");

// Buscar treinos do professor logado
$sql = "
SELECT t.id, t.data, t.horario, tur.nome AS turma_nome
FROM treinos t
JOIN turmas tur ON t.turma_id = tur.id
WHERE t.professor_id = $professor_id
ORDER BY t.data DESC, t.horario DESC
";
$treinos = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Gerenciar Treinos</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Ícones -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">

<!-- Favicon -->
<link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">

<style>
body {
    margin: 0;
    padding: 0;
    font-family: 'Fredoka', sans-serif;
    background: linear-gradient(to bottom, #6a0dad 0%, #000000 100%);
    color: #fff;
    min-height: 100vh;
    overflow-x: hidden;
    overflow-y: auto;
}

.logo-header {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 30px 0;
}
.logo {
    width: 130px;
    height: auto;
}

.container {
    background: #fff;
    border-radius: 16px;
    max-width: 720px;
    width: 90%;
    padding: 30px 35px;
    box-shadow: 0 4px 20px rgba(111, 45, 168, 0.3);
    color: #4b0082;
    margin: 20px auto;
}
h1 {
    text-align: center;
    font-weight: 700;
    font-size: 2rem;
    margin-bottom: 30px;
    color: #000;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
}

/* Formulário */
form {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 40px;
    justify-content: space-between;
}
form label {
    flex: 1 0 100%;
    font-weight: 500;
    margin-bottom: 8px;
    color: #4b0082;
}
form input[type="date"],
form input[type="time"],
form select {
    flex: 1 1 calc(33% - 15px);
    padding: 14px 16px;
    font-size: 16px;
    border: 2px solid #000;
    border-radius: 14px;
    color: #4b0082;
    background: #fff;
}
form button {
    flex: 1 0 100%;
    padding: 18px 0;
    font-size: 1.2rem;
    font-weight: 700;
    background-color: #ffd700;
    color: #4b0082;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    transition: transform 0.2s, background-color 0.3s;
}
form button:hover {
    background-color: #ffe345;
    transform: translateY(-3px);
}

/* Tabela */
.table-wrapper { overflow-x:auto; border-radius:16px; }
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 14px;
}
thead {
    background-color: rgba(0,0,0,0.4);
    color: #fff;
    font-weight: 700;
}
th, td {
    padding: 14px 10px;
    text-align: center;
    background: rgba(255,255,255,0.9);
    color: #4b0082;
    font-weight: 600;
    border-radius: 12px;
}
td:last-child {
    display: flex;
    justify-content: center;
    gap: 12px;
}

/* Botões da tabela */
a.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 14px;
    text-decoration: none;
    transition: transform 0.2s, background-color 0.3s;
}
a.btn-edit {
    background-color: #2980b9;
    color: #fff;
}
a.btn-edit:hover {
    background-color: #1c5980;
    transform: translateY(-2px);
}
.btn-delete {
    background-color: #e74c3c; 
    border: none;
    color: white;
    transition: background-color 0.3s ease, transform 0.2s ease;
}
.btn-delete:hover {
    background-color: #b8362a; 
    color: white;
    transform: scale(1.1);
}
.btn-delete i, .btn-edit i { color: white !important; }

/* Mensagem vazio */
.empty {
    text-align: center;
    font-size: 1.3rem;
    padding: 40px 0;
    color: #000000ff;
}

/* Responsividade */
@media(max-width:720px){
    form input, form select { flex:1 0 100%; }
    td, th { font-size:14px; padding:10px 8px; }
}
@media(max-width:480px){
    table, thead, tbody, th, td, tr { display:block; }
    thead tr { display:none; }
    tbody tr {
        margin-bottom:15px;
        padding:15px 20px;
        border-radius:16px;
        box-shadow:0 3px 12px rgba(0,0,0,0.15);
        background: rgba(255,255,255,0.9);
    }
    tbody td {
        padding-left: 120px;
        position: relative;
        text-align: left;
    }
    tbody td::before {
        content: attr(data-label);
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        font-weight:700;
        color:#6a0dad;
    }
}
</style>
</head>
<body>

<!-- Logo -->
<header class="logo-header">
  <img src="../../imgs/logo.png" alt="New Football Logo" class="logo" />
</header>

<!-- Navbar -->
<?php include 'nav_professor.php'; ?>

<div class="container">
  <h1>Gerenciar Treinos</h1>

  <!-- Formulário -->
  <form method="POST">
    <label for="data">Data do Treino:</label>
    <input id="data" type="date" name="data" required />

    <label for="horario">Horário:</label>
    <input id="horario" type="time" name="horario" required />

    <label for="turma_id">Turma:</label>
    <select id="turma_id" name="turma_id" required>
      <option value="">Selecione a turma</option>
      <?php while ($turma = $t_result->fetch_assoc()): ?>
        <option value="<?= $turma['id'] ?>"><?= htmlspecialchars($turma['nome']) ?></option>
      <?php endwhile; ?>
    </select>

    <button type="submit">Adicionar Treino</button>
  </form>

  <!-- Tabela -->
  <?php if ($treinos->num_rows > 0): ?>
    <table>
      <thead>
        <tr>
          <th>Data</th>
          <th>Horário</th>
          <th>Turma</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($t = $treinos->fetch_assoc()): ?>
          <tr>
            <td data-label="Data"><?= date('d/m/Y', strtotime($t['data'])) ?></td>
            <td data-label="Horário"><?= date('H:i', strtotime($t['horario'])) ?></td>
            <td data-label="Turma"><?= htmlspecialchars($t['turma_nome']) ?></td>
            <td>
              <a href="editar_treino.php?id=<?= $t['id'] ?>" class="btn btn-edit"><i class="bi bi-pencil-square"></i></a>
              <a href="#" class="btn btn-delete" onclick="openDeleteModal(<?= $t['id'] ?>)"><i class="bi bi-trash"></i></a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p class="empty">Nenhum treino cadastrado</p>
  <?php endif; ?>
</div>

<!-- Modal de Exclusão -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-dark">
      <div class="modal-header" style="background-color:#6a0dad; color:#ffd700;">
        <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill"></i> Confirmar Exclusão</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Tem certeza que deseja excluir este treino?<br>
        <strong style="color:#6a0dad;">Esta ação não poderá ser desfeita.</strong>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" style="background:#ffd700; color:#4b0082;" data-bs-dismiss="modal">Cancelar</button>
        <a id="confirmDeleteBtn" href="#" class="btn" style="background:#6a0dad; color:#fff;">Excluir</a>
      </div>
    </div>
  </div>
</div>

<script src="../../js/nav_professor.js"></script>
<script>
function openDeleteModal(id) {
  const deleteUrl = "?excluir=" + id;
  document.getElementById("confirmDeleteBtn").setAttribute("href", deleteUrl);
  const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
  modal.show();
}
</script>

</body>
</html>
