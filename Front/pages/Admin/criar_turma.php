<?php
include '../../../Back/conexao.php';
session_start();

// Verifica se o admin está logado
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: admin.php");
    exit;
}

// Mensagem de feedback
$msg = "";
$msg_type = "";

// Quando o formulário for enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $horario = $_POST['horario'];
    $dias_treino = trim($_POST['dias_treino']);
    $professores = $_POST['professores'] ?? [];

    if ($nome && $horario && $dias_treino && !empty($professores)) {
        // 1. Inserir turma
        $stmt = $conn->prepare("INSERT INTO turmas (nome, horario, dias_treino) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $horario, $dias_treino);

        if ($stmt->execute()) {
            $turma_id = $stmt->insert_id;
            $stmt->close();

            // 2. Inserir professores na tabela turma_professor
            $stmt2 = $conn->prepare("INSERT INTO turma_professor (turma_id, professor_id) VALUES (?, ?)");
            foreach ($professores as $prof_id) {
                $stmt2->bind_param("ii", $turma_id, $prof_id);
                $stmt2->execute();
            }
            $stmt2->close();

            $msg = "Turma cadastrada com sucesso!";
            $msg_type = "success";
        } else {
            $msg = "Erro ao cadastrar turma: " . $stmt->error;
            $msg_type = "error";
        }
    } else {
        $msg = "Preencha todos os campos e selecione pelo menos 1 professor!";
        $msg_type = "error";
    }
}

// Busca todos os professores
$professores = $conn->query("SELECT id, nome FROM professores ORDER BY nome ASC");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Cadastrar Turma</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body { margin:0; font-family: Arial, sans-serif; background: linear-gradient(135deg,#000,#4c0070); color:white; display:flex; justify-content:center; align-items:center; min-height:100vh; padding:15px; }
h1 { margin-bottom:30px; text-align:center; color:#FFD700; text-transform:uppercase; text-shadow: 2px 2px 5px rgba(0,0,0,0.5); }
.form-container { background:linear-gradient(135deg,#7a0ea4,#a020f0); border-radius:15px; padding:30px; width:100%; max-width:500px; box-shadow:0 6px 15px rgba(0,0,0,0.5); }
.form-container form { display:flex; flex-direction:column; gap:15px; }
label { font-weight:bold; }
input[type="text"], input[type="time"], select { padding:10px; border-radius:8px; border:none; font-size:1rem; }
.checkbox-group { display:flex; flex-wrap: wrap; gap:10px; max-height:200px; overflow-y:auto; padding:5px; background:rgba(0,0,0,0.2); border-radius:8px; }
.checkbox-group label { font-weight:normal; }
input[type="submit"] { background:#FFD700; color:#4b0082; border:none; padding:12px; border-radius:10px; font-size:1.2rem; cursor:pointer; transition:all 0.3s ease; }
input[type="submit"]:hover { background:#e6c200; }
.msg { padding:12px; border-radius:10px; font-weight:bold; text-align:center; margin-bottom:15px; }
.msg.success { background-color: #90ee90; color: #000; }
.msg.error { background-color: #ff4d4d; color: #fff; }
button.back { background-color: transparent; color:#FFD700; border:none; font-size:1rem; margin-top:10px; cursor:pointer; text-decoration:underline; }
button.back:hover { color:#fff; }
</style>
</head>
<body>

<div class="form-container">
    <h1>Cadastrar Nova Turma</h1>

    <?php if($msg): ?>
        <div class="msg <?= $msg_type ?>" id="alerta"><?= $msg ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <label for="nome">Nome da Turma:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="horario">Horário:</label>
        <input type="time" id="horario" name="horario" required>

        <label for="dias_treino">Dias de Treino:</label>
        <input type="text" id="dias_treino" name="dias_treino" placeholder="Ex: Seg, Qua, Sex" required>

        <label>Professores:</label>
        <div class="checkbox-group">
            <?php while($p = $professores->fetch_assoc()): ?>
                <label>
                    <input type="checkbox" name="professores[]" value="<?= $p['id'] ?>"> <?= $p['nome'] ?>
                </label>
            <?php endwhile; ?>
        </div>

        <input type="submit" value="Cadastrar Turma">
    </form>

    <button class="back" onclick="location.href='turmas_admin.php'">← Voltar</button>
</div>

<script>
const alerta = document.getElementById('alerta');
if(alerta){ setTimeout(()=>{ alerta.style.display='none'; },5000); }
</script>

</body>
</html>
