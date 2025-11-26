<?php
session_start();

// Verifica se o admin está logado
if(!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true){
    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Home</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="../../../Front/imgs//logo.png" type="image/x-icon">
<style>
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
    padding: 0 15px;
}

h1 {
    margin-bottom: 50px;
    font-size: 2.5rem;
    font-weight: bold;
    text-transform: uppercase;
    text-align: center;
    color: #ffd700;
    text-shadow: 2px 2px 5px rgba(0,0,0,0.5);
}

.buttons-container {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    justify-content: center;
    width: 100%;
    max-width: 650px;
}

button {
    flex: 1;
    min-width: 200px;
    padding: 20px;
    font-size: 1.2rem;
    font-weight: bold;
    border-radius: 15px;
    border: none;
    cursor: pointer;
    background: linear-gradient(135deg, #7a0ea4, #a020f0);
    color: white;
    transition: all 0.3s ease;
}

button:hover {
    background: linear-gradient(135deg, #a020f0, #b356f5);
    transform: scale(1.05);
}
</style>
</head>
<body>

<h1>Bem-vindo, CEO</h1>

<div class="buttons-container">
    <button onclick="location.href='CriarContaAluno.php'">Cadastrar Aluno</button>
    <button onclick="location.href='CriarContaProfessor.php'">Cadastrar Professor</button>
    <button onclick="location.href='campeonato.php'">Campeonato</button>
    <button onclick="location.href='gerenciar_aluno_admin.php'">Gerenciar Alunos</button>
    <button onclick="location.href='turmas_admin.php'">Turma</button>
    <button onclick="location.href=' ../../Divulgacao_New/index.php'">Sair</button>
</div>

</body>
</html>
