<?php
session_start();

if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: admin.php");
    exit;
}

include '../../../Back/conexao.php';

// Corrigindo a consulta
$sql = "SELECT * FROM campeonatos ORDER BY data_inicio ASC";
$result = $conn->query($sql);

$campeonatos = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $campeonatos[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Listar Campeonatos</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css">
<link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">
<style>
* { box-sizing: border-box; }

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #000000, #4c0070);
    color: white;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
}

h1 {
    margin-bottom: 30px;
    font-size: 2.5rem;
    font-weight: bold;
    text-align: center;
    color: #FFD700;
    text-shadow: 2px 2px 5px rgba(0,0,0,0.5);
    text-transform: uppercase;
}

.container {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    justify-content: center;
    width: 100%;
    max-width: 1200px;
}

.card {
    background: linear-gradient(135deg,#7a0ea4,#a020f0);
    border-radius: 15px;
    padding: 25px 20px;
    width: 280px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.5);
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
    margin: 4px 0;
}

.card .buttons {
    display: flex;
    gap: 8px;
    margin-top: 10px;
    flex-wrap: wrap;
    justify-content: center;
}

.card button {
    padding: 8px 12px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: bold;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 5px;
}

.card button.editar {
    background-color: #FFD700;
    color: #4b0082;
}

.card button.editar:hover {
    background-color: #e6c200;
}

.card button.excluir {
    background-color: #ff4d4d;
    color: #fff;
}

.card button.excluir:hover {
    background-color: #ff6666;
}

.card button.jogadores {
    background-color: #4CAF50;
    color: white;
}

.card button.jogadores:hover {
    background-color: #45a049;
}

.no-campeonatos {
    text-align: center;
    color: #fff;
    font-size: 1.2rem;
    margin-top: 50px;
}

.link {
    text-align: center;
    margin: 20px 0;
}

.link a {
    color: #FFD700;
    font-weight: bold;
    text-decoration: none;
    font-size: 1rem;
}

.link a:hover {
    text-decoration: underline;
}

@media (max-width: 480px) {
    h1 { font-size: 2rem; margin-bottom: 25px; }
    .card { width: 90%; padding: 20px; }
    .card .buttons { gap: 5px; }
}
</style>
</head>
<body>

<h1>Campeonatos Cadastrados</h1>

<div class="container">
<?php if (count($campeonatos) > 0): ?>
    <?php foreach ($campeonatos as $c): ?>
        <div class="card">
            <h2><?= htmlspecialchars($c['nome']) ?></h2>
            <p><strong>Data de Início:</strong> <?= date('d/m/Y', strtotime($c['data_inicio'])) ?></p>
            <p><strong>Idade Máxima:</strong> <?= htmlspecialchars($c['idade_maxima']) ?></p>
            <div class="buttons">
                <button class="editar" onclick="location.href='editar_campeonato.php?id=<?= $c['id'] ?>'">
                    <i class="bi bi-pencil-square"></i> Editar
                </button>
                <button class="excluir" onclick="if(confirm('Deseja realmente excluir este campeonato?')) location.href='excluir_campeonato.php?id=<?= $c['id'] ?>'">
                    <i class="bi bi-trash-fill"></i> Excluir
                </button>
                <button class="jogadores" onclick="location.href='listar_convocados.php?campeonato_id=<?= $c['id'] ?>'">
                    <i class="bi bi-people-fill"></i> Jogadores
                </button>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="no-campeonatos">Nenhum campeonato cadastrado.</p>
<?php endif; ?>
</div>

<div class="link">
    <a href="campeonato.php">← Voltar</a>
</div>

</body>
</html>
