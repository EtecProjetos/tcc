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
<title>Gerenciar Turmas</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">
<style>
* { box-sizing: border-box; }

body {
    margin: 0;
    background: linear-gradient(135deg, #000000, #4c0070);
    color: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
}

h1 {
    margin-bottom: 40px;
    font-size: 2.5rem;
    font-weight: bold;
    text-transform: uppercase;
    text-align: center;
    color: #FFD700;
    text-shadow: 2px 2px 5px rgba(0,0,0,0.5);
}

.container {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    justify-content: center;
    width: 100%;
    max-width: 1000px;
}

.card {
    background: linear-gradient(135deg,#7a0ea4,#a020f0);
    border-radius: 15px;
    padding: 25px 20px;
    width: 250px;
    height: 200px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.5);
    cursor: pointer;
    transition: transform 0.3s, background 0.3s, box-shadow 0.3s;
}

.card:hover {
    transform: scale(1.05);
    background: linear-gradient(135deg,#a020f0,#6e007f);
    box-shadow: 0 6px 18px rgba(0,0,0,0.6);
}

.card h2 {
    color: #FFD700;
    margin-bottom: 12px;
    font-size: 1.5rem;
    text-transform: uppercase;
}

.card p {
    color: #fff;
    font-size: 1rem;
    line-height: 1.3rem;
}

/* Responsividade */
@media (max-width: 480px) {
    .card { width: 90%; padding: 20px; height: auto; }
    h1 { font-size: 2rem; margin-bottom: 30px; }
}
</style>
</head>
<body>

<h1>Gerenciar Turmas</h1>

<div class="container">
    <div class="card" onclick="location.href='criar_turma.php'">
        <h2>Cadastrar Turma</h2>
        <p>Crie uma nova turma e defina seu horário e professor.</p>
    </div>

    <div class="card" onclick="location.href='ver_turmas.php'">
        <h2>Ver Turmas</h2>
        <p>Visualize todas as turmas cadastradas no sistema.</p>
    </div>

    <div class="card" onclick="location.href='Adicionar_aluno_turma.php'">
        <h2>Adicionar Aluno à Turma</h2>
        <p>Vincule alunos a uma turma existente.</p>
    </div>

    <div class="card" onclick="location.href='home_admin.php'">
        <h2>Voltar</h2>
    </div>
</div>

</body>
</html>
