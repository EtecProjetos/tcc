<?php
session_start();

// Verifica se o admin está logado
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: admin.php");
    exit;
}

$msg = "";
$msg_type = "";

// Quando o formulário for enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include '../../../back/conexao.php';

    $nome = $_POST['nome'] ?? '';
    $data_inicio = $_POST['data_inicio'] ?? '';
    $idade_maxima = $_POST['idade_maxima'] ?? '';

    if (!empty($nome) && !empty($data_inicio) && !empty($idade_maxima)) {
        // Prepara a query
        $stmt = $conn->prepare("INSERT INTO campeonatos (nome, data_inicio, idade_maxima) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $nome, $data_inicio, $idade_maxima);

        if ($stmt->execute()) {
            $msg = "Campeonato cadastrado com sucesso!";
            $msg_type = "success";
        } else {
            $msg = "Erro ao cadastrar campeonato: " . $stmt->error;
            $msg_type = "error";
        }

        $stmt->close();
    } else {
        $msg = "Preencha todos os campos obrigatórios!";
        $msg_type = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Cadastrar Campeonato - New Football</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">
<style>
* { box-sizing: border-box; }

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #000000, #4c0070);
    color: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 15px;
}

h1 {
    margin-bottom: 40px;
    font-size: 2.3rem;
    text-transform: uppercase;
    color: #FFD700;
    text-shadow: 2px 2px 5px rgba(0,0,0,0.6);
}

.form-container {
    background: linear-gradient(135deg,#7a0ea4,#a020f0);
    border-radius: 15px;
    padding: 30px 25px;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.5);
}

form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

label {
    font-weight: bold;
}

input[type="text"],
input[type="date"],
input[type="number"] {
    padding: 10px;
    border-radius: 8px;
    border: none;
    font-size: 1rem;
}

input[type="submit"] {
    background: #FFD700;
    color: #4b0082;
    border: none;
    padding: 12px;
    border-radius: 10px;
    font-size: 1.2rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

input[type="submit"]:hover {
    background: #e6c200;
}

button.back {
    background-color: transparent;
    color: #FFD700;
    border: none;
    font-size: 1rem;
    margin-top: 10px;
    cursor: pointer;
    text-decoration: underline;
}

button.back:hover {
    color: #fff;
}

.msg {
    padding: 12px;
    border-radius: 10px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 15px;
}

.msg.success { background-color: #90ee90; color: #000; }
.msg.error { background-color: #ff4d4d; color: #fff; }

@media (max-width:480px){
    .form-container { padding: 20px 15px; }
    input[type="submit"], button.back { font-size: 1rem; padding: 10px; }
}
</style>
</head>
<body>

<h1>Cadastrar Campeonato</h1>

<div class="form-container">
    <?php if ($msg): ?>
        <div class="msg <?= $msg_type ?>" id="alerta"><?= $msg ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="nome">Nome do Campeonato:</label>
        <input type="text" name="nome" id="nome" required>

        <label for="data_inicio">Data de Início:</label>
        <input type="date" name="data_inicio" id="data_inicio" required>

        <label for="idade_maxima">Idade Máxima:</label>
        <input type="number" name="idade_maxima" id="idade_maxima" required>

        <input type="submit" value="Cadastrar Campeonato">
    </form>

    <button class="back" onclick="location.href='campeonato.php'">← Voltar</button>
</div>

<script>
const alerta = document.getElementById('alerta');
if (alerta) {
    setTimeout(() => { alerta.style.display = 'none'; }, 5000);
}
</script>

</body>
</html>
