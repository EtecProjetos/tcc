<?php
include '../../../Back/conexao.php';
session_start();

if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: admin.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: ver_turmas.php");
    exit;
}

$turma_id = intval($_GET['id']);
$mensagem = "";

// busca a turma
$stmt = $conn->prepare("SELECT * FROM turmas WHERE id = ?");
$stmt->bind_param("i", $turma_id);
$stmt->execute();
$result = $stmt->get_result();
$turma = $result->fetch_assoc();

if (!$turma) die("Turma não encontrada.");

// busca professores
$professores = $conn->query("SELECT id, nome FROM professores ORDER BY nome ASC");

// atualizar turma
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_turma'])) {
    $nome = trim($_POST['nome']);
    $horario = $_POST['horario'];
    $dias_treino = trim($_POST['dias_treino']);
    $professor_id = $_POST['professor_id'];

    if (!empty($nome) && !empty($horario) && !empty($dias_treino) && !empty($professor_id)) {
        $stmt = $conn->prepare("UPDATE turmas SET nome=?, horario=?, dias_treino=?, professor_id=? WHERE id=?");
        $stmt->bind_param("sssii", $nome, $horario, $dias_treino, $professor_id, $turma_id);
        if ($stmt->execute()) {
            $mensagem = "✅ Turma atualizada com sucesso!";
            $turma['nome'] = $nome;
            $turma['horario'] = $horario;
            $turma['dias_treino'] = $dias_treino;
            $turma['professor_id'] = $professor_id;
        } else {
            $mensagem = "❌ Erro ao atualizar turma.";
        }
    } else {
        $mensagem = "⚠️ Preencha todos os campos.";
    }
}

// remover aluno da turma
if (isset($_GET['remover_aluno'])) {
    $aluno_id = intval($_GET['remover_aluno']);
    $stmt = $conn->prepare("UPDATE alunos SET turma_id = NULL WHERE id = ?");
    $stmt->bind_param("i", $aluno_id);
    $stmt->execute();
    header("Location: editar_turma.php?id=$turma_id");
    exit;
}

// lista de alunos da turma
$alunos = $conn->query("SELECT id, nome FROM alunos WHERE turma_id = $turma_id ORDER BY nome ASC");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Turma</title>
<link rel="shortcut icon" href="../../../Front/imgs/logo.png" type="image/x-icon">
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
<style>
* { box-sizing: border-box; }

body {
    margin: 0;
    font-family: 'Fredoka', sans-serif;
    background: linear-gradient(135deg, #000000, #4c0070);
    color: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
    min-height: 100vh;
}

h1 {
    font-size: 2.5rem;
    font-weight: bold;
    color: #FFD700;
    text-shadow: 2px 2px 5px rgba(0,0,0,0.5);
    text-align: center;
    margin-bottom: 25px;
}

.card {
    background: linear-gradient(135deg,#7a0ea4,#a020f0);
    border-radius: 15px;
    padding: 25px;
    width: 100%;
    max-width: 600px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.5);
    margin-bottom: 25px;
}

.card h2 {
    color: #FFD700;
    margin-bottom: 15px;
    text-align: center;
}

form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

input[type="text"], input[type="time"], select, button {
    padding: 12px;
    border-radius: 12px;
    border: none;
    font-size: 1rem;
    outline: none;
}

button {
    background: linear-gradient(90deg,#6a0dad,#8e2de2);
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: linear-gradient(90deg,#8e2de2,#6a0dad);
    transform: scale(1.05);
}

.mensagem {
    text-align: center;
    color: #FFD700;
    font-weight: bold;
    margin-bottom: 15px;
}

table {
    width: 100%;
    border-collapse: collapse;
    color: #fff;
    text-align: center;
}

th, td {
    padding: 12px 8px;
    border-bottom: 1px solid rgba(255,255,255,0.2);
}

th {
    background-color: rgba(255,255,255,0.15);
    font-weight: 600;
    text-transform: uppercase;
}

tr:hover {
    background-color: rgba(255,255,255,0.1);
}

.btn-remover {
    background: #ff4d4d;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
    text-decoration: none;
    display: inline-block;
}

.btn-remover:hover {
    background: #ff1a1a;
    transform: scale(1.05);
}

.voltar {
    text-decoration: none;
    color: #FFD700;
    font-weight: bold;
    margin-top: 15px;
    display: inline-block;
    transition: 0.3s;
}

.voltar:hover { color: #fff; }

.vazio {
    text-align: center;
    font-style: italic;
    padding: 12px;
    color: #ffeb99;
}

/* Responsividade */
@media(max-width:768px){
    .card { padding: 20px; }
    input[type="text"], input[type="time"], select, button { font-size: 0.95rem; padding: 10px; }
}

@media(max-width:480px){
    h1 { font-size: 2rem; }
}
</style>
</head>
<body>

<h1>Editar Turma: <?= htmlspecialchars($turma['nome']) ?></h1>

<?php if($mensagem): ?>
    <p class="mensagem"><?= $mensagem ?></p>
<?php endif; ?>

<div class="card">
    <h2>Informações da Turma</h2>
    <form method="POST">
        <input type="hidden" name="update_turma">
        <input type="text" name="nome" placeholder="Nome da Turma" value="<?= htmlspecialchars($turma['nome']) ?>" required>
        <input type="time" name="horario" value="<?= htmlspecialchars($turma['horario']) ?>" required>
        <input type="text" name="dias_treino" placeholder="Ex: Seg, Qua, Sex" value="<?= htmlspecialchars($turma['dias_treino']) ?>" required>

        <select name="professor_id" required>
            <option value="">Selecione o Professor</option>
            <?php while($p = $professores->fetch_assoc()): ?>
                <option value="<?= $p['id'] ?>" <?= $turma['professor_id']==$p['id']?'selected':'' ?>>
                    <?= htmlspecialchars($p['nome']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Salvar Alterações</button>
    </form>
</div>

<a href="ver_turmas.php" class="voltar">← Voltar</a>

</body>
</html>
