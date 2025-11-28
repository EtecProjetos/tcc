<?php
session_start();
include '../../../Back/conexao.php';

// Impede cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Verifica login do professor
if (!isset($_SESSION['professor_id'])) {
    header("Location: loginProfessor.php");
    exit();
}

$professor_id = $_SESSION['professor_id'];

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Busca dados do professor para exibir
$stmt = $conn->prepare("
    SELECT nome, data_nascimento, cpf, email, telefone
    FROM professores
    WHERE id = ?
");
$stmt->bind_param("i", $professor_id);
$stmt->execute();
$res = $stmt->get_result();
$professor = $res->fetch_assoc();
$stmt->close();

if (!$professor) die("Professor não encontrado.");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>Perfil do Professor</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />

<style>
body {
    background: linear-gradient(to bottom, #6a0dad 0%, #000000 100%);
    color: white;
    font-family: 'Fredoka', sans-serif;
    margin: 0;
    padding-bottom: 120px;
}
.container {
    max-width: 600px;
    margin: 30px auto 100px auto;
    background: rgba(138, 58, 185, 0.9);
    border-radius: 15px;
    padding: 20px 30px 40px 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.5);
}
.container:hover {
    box-shadow: 0 0 15px 5px rgba(0, 0, 0, 0.7);
    transition: box-shadow 0.5s ease;
}
h1 { text-align: center; margin-bottom: 25px; font-weight: bold; }
label { display: block; margin-top: 15px; font-weight: bold; }
input[type=text], input[type=date], input[type=email], input[type=tel] {
    width: 100%; padding: 10px; margin-top: 6px; border-radius: 8px;
    border: none; font-size: 1em; box-sizing: border-box; background-color: #d8b4f8; color: #4b0082;
}
input[readonly] {
    background-color: #e3c9ff;
    cursor: not-allowed;
}
@media (max-width: 650px) {
    .container { margin: 15px 15px 100px 15px; padding: 15px 20px 30px 20px; }
}
header.logo-header { margin-bottom: 30px; }
header.logo-header .logo { width: 180px; display: block; margin: 0 auto; }
.btn_voltar {
    margin-top: 20px;
    background-color: #ffd700;
    border: none;
    color: #4b0082;
    font-weight: bold;
    font-size: 1.2em;
    padding: 12px;
    border-radius: 25px;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.3s ease;
}
.btn_voltar:hover { background-color: #ffe34d; }
</style>
</head>
<header class="logo-header">
    <img src="../../imgs/logo.png" alt="New Football Logo" class="logo" />
</header>
<body>

<div class="container">
    <h1>Perfil do Professor</h1>

    <form>
        <label for="nome">Nome</label>
        <input type="text" id="nome" readonly value="<?= h($professor['nome']) ?>" />

        <label for="data_nascimento">Data de Nascimento</label>
        <input type="date" id="data_nascimento" readonly value="<?= h($professor['data_nascimento']) ?>" />

        <label for="cpf">CPF</label>
        <input type="text" id="cpf" readonly value="<?= h($professor['cpf']) ?>" />

        <label for="email">E-mail</label>
        <input type="email" id="email" readonly value="<?= h($professor['email']) ?>" />

        <label for="telefone">Telefone</label>
        <input type="tel" id="telefone" readonly value="<?= h($professor['telefone']) ?>" />
    </form>
</div>


<?php include './nav_professor.php'; ?>
<script src="../../js/nav_professor.js"></script>

</body>
</html>
