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
<title>Campeonatos</title>
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

<h1>Gestão de Campeonatos</h1>

<div class="container">
    <div class="card" onclick="location.href='listar_campeonato.php'">
        <h2>Listar Campeonatos</h2>
        <p>Veja todos os campeonatos cadastrados.</p>
    </div>

    <div class="card" onclick="location.href='cadastrar_campeonato.php'">
        <h2>Cadastrar Campeonatos</h2>
        <p>Adicione novos campeonatos ao sistema.</p>
    </div>

    <div class="card" onclick="location.href='convocar_usuario.php'">
        <h2>Convocar Campeonatos</h2>
        <p>Convocar alunos e professores para campeonatos.</p>
    </div>
        <div class="card" onclick="location.href='home_admin.php'">
        <h2>Voltar</h2>
       
    </div>
</div>

</body>
</html>
