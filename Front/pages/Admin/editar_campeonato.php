<?php
session_start();

if(!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true){
    header("Location: admin.php");
    exit;
}

include '../../../back/conexao.php';

// Pega o ID do campeonato
$id = intval($_GET['id'] ?? 0);
$msg = "";

// Atualiza dados se formulário enviado
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nome = $_POST['nome'] ?? '';
    $data_inicio = $_POST['data_inicio'] ?? '';
    $idade_maxima = $_POST['idade_maxima'] ?? '';

    if($nome && $data_inicio && $idade_maxima){
        $stmt = $conn->prepare("UPDATE campeonatos 
                                SET nome=?, data_inicio=?, idade_maxima=? 
                                WHERE id=?");
        $stmt->bind_param("ssii", $nome, $data_inicio, $idade_maxima, $id);

        if($stmt->execute()){
            $msg = "Campeonato atualizado com sucesso!";
        } else {
            $msg = "Erro ao atualizar: " . $stmt->error;
        }
    } else {
        $msg = "Preencha todos os campos obrigatórios!";
    }
}

// Busca dados atuais do campeonato
$stmt = $conn->prepare("SELECT * FROM campeonatos WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$campeonato = $result->fetch_assoc();

if(!$campeonato){
    die("Campeonato não encontrado!");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Editar Campeonato - New Football</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css">
<link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">
<style>
* { box-sizing: border-box; }

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg,#000,#4c0070);
    color: white;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.form-container {
    background: linear-gradient(135deg,#7a0ea4,#a020f0);
    padding: 30px 25px;
    border-radius: 15px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.5);
    width: 100%;
    max-width: 400px;
    text-align: center;
}

.form-container h2 {
    color: #FFD700;
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
}

.input-group {
    display: flex;
    flex-direction: column;
    text-align: left;
    margin-bottom: 15px;
}

.input-group label {
    margin-bottom: 5px;
    font-weight: bold;
}

.input-group input {
    padding: 10px;
    border-radius: 10px;
    border: none;
    font-size: 1rem;
}

.input-group input:focus {
    outline: none;
    box-shadow: 0 0 5px #ffd700;
}

button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 12px;
    font-weight: bold;
    font-size: 1rem;
    cursor: pointer;
    margin-top: 10px;
    transition: background-color 0.3s;
}

button.save {
    background-color: #FFD700;
    color: #4b0082;
}

button.save:hover {
    background-color: #e6c200;
}

button.back {
    background-color: transparent;
    color: #FFD700;
    margin-top: 10px;
    font-size: 0.95rem;
    text-decoration: underline;
}

button.back:hover {
    color: #fff;
}

.msg {
    margin-bottom: 10px;
    font-weight: bold;
    padding: 10px;
    border-radius: 10px;
}

.msg.success { background-color: #90ee90; color: #000; }
.msg.error { background-color: #ff4d4d; color: #fff; }

@media (max-width:480px){
    .form-container { padding: 20px 15px; }
    button { font-size: 0.95rem; padding: 10px; }
}
</style>
</head>
<body>

<div class="form-container">
    <h2>Editar Campeonato</h2>

    <?php if($msg): ?>
        <div class="msg <?= strpos($msg,'sucesso')!==false?'success':'error' ?>" id="alerta">
            <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="input-group">
            <label><i class="bi bi-pencil-fill"></i> Nome:</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($campeonato['nome']) ?>" required>
        </div>

        <div class="input-group">
            <label><i class="bi bi-calendar-fill"></i> Data de Início:</label>
            <input type="date" name="data_inicio" value="<?= htmlspecialchars($campeonato['data_inicio']) ?>" required>
        </div>

        <div class="input-group">
            <label><i class="bi bi-person-fill"></i> Idade Máxima:</label>
            <input type="number" name="idade_maxima" value="<?= htmlspecialchars($campeonato['idade_maxima']) ?>" required>
        </div>

        <button type="submit" class="save"><i class="bi bi-save-fill"></i> Salvar Alterações</button>
        <button type="button" class="back" onclick="location.href='listar_campeonato.php'"><i class="bi bi-arrow-left"></i> Voltar</button>
    </form>
</div>

<script>
// Alerta some depois de 5 segundos
const alerta = document.getElementById('alerta');
if(alerta){
    setTimeout(() => { alerta.style.display = 'none'; }, 5000);
}
</script>

</body>
</html>
