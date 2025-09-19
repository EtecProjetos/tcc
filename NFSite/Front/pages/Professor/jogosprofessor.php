<?php
session_start();
include '../back/conexao.php';

if (!isset($_SESSION['professor_id'])) {
    header("Location: loginProfessor.php");
    exit();
}

$professor_id = $_SESSION['professor_id'];

$conn->query("DELETE FROM jogos WHERE data < CURDATE()");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data'], $_POST['horario'], $_POST['categoria'], $_POST['local'], $_POST['adversario'], $_POST['logo_url'], $_POST['turma_id'], $_POST['tipo'])) {
    $data = $_POST['data'];
    $horario = $_POST['horario'];
    $categoria = strtoupper($_POST['categoria']);
    $local = strtoupper($_POST['local']);
    $adversario = strtoupper($_POST['adversario']);
    $logo_url = $_POST['logo_url'];
    $turma_id = $_POST['turma_id'];
    $tipo = $_POST['tipo'];

    $stmt = $conn->prepare("INSERT INTO jogos (data, horario, categoria, local, adversario, logo_url, professor_id, turma_id, tipo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssiss", $data, $horario, $categoria, $local, $adversario, $logo_url, $professor_id, $turma_id, $tipo);
    $stmt->execute();
    $stmt->close();

    header("Location: jogosprofessor.php");
    exit();
}

if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $conn->query("DELETE FROM jogos WHERE id = $id AND professor_id = $professor_id");
    header("Location: jogosprofessor.php");
    exit();
}

$turmas_result = $conn->query("SELECT id, nome FROM turmas WHERE professor_id = $professor_id");

$sql = "
SELECT j.id, j.data, j.horario, j.categoria, j.local, j.adversario, j.logo_url, j.tipo, t.nome AS turma_nome
FROM jogos j
JOIN turmas t ON j.turma_id = t.id
WHERE j.professor_id = $professor_id
ORDER BY j.data DESC
";
$jogos = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Jogos</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />
<link rel="shortcut icon" href="imgs/logo.png" type="image/x-icon">

<style>
    
body {
    margin: 0;
    font-family: 'Roboto', Arial, sans-serif;
    background: linear-gradient(to bottom, #6a0dad 0%, #000000 100%);
    color: #fff;
    min-height: 100vh;
    padding: 20px;
        overflow-x: hidden;       /* evita scroll horizontal */
    overflow-y: auto;  
}

/* Título */
h1 {
    text-align: center;
    margin-bottom: 30px;
    color: #fff;
}

/* Container principal dividido */
.main-container {
    display: flex;
    gap: 20px;
    justify-content: center;
    align-items: flex-start;
        margin-bottom: 50px;  
}

/* Colunas */
.left-column {
    flex: 1.2;
}

.right-column {
    flex: 1;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

/* Cards */
.card {
    background: #fff;
    color: #4b0082;
    border-radius: 16px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    box-shadow: 0 4px 15px rgba(111,45,168,0.3);
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(111,45,168,0.5);
}

/* Card adicionar maior */
.card.add-card {
    min-height: 600px;
}

/* Card jogos menores */
.card.game-card {
    min-height: 350px;
}

/* Form inputs */
.card label {
    font-weight: 500;
    margin-top: 5px;
}

.card input, .card select {
    padding: 10px;
    font-size: 0.95rem;
    border: 2px solid #6f2da8;
    border-radius: 10px;
    color: #4b0082;
}

.card input:focus, .card select:focus {
    border-color: #390062;
    outline: none;
}

.card button {
    margin-top: auto;
    padding: 10px;
    font-weight: 700;
    border-radius: 15px;
    cursor: pointer;
    width: 100%;
    text-align: center;
    display: inline-block;
    transition: all 0.3s;
    font-size: 1rem;
    background-color: #6f2da8;
    color: white;
    border: none;
}

.card button:hover { background-color: #551b9a; }

/* Botões editar/excluir apenas ícones */
.card .actions {
    margin-top: auto;
    display: flex;
    justify-content: center;
    gap: 10px;
}

.card .actions a {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    color: white;
    text-decoration: none;
    font-size: 1.2rem;
    transition: all 0.3s;
}

.card .actions a.edit { background-color: #2980b9; }
.card .actions a.edit:hover { background-color: #1c5980; }

.card .actions a.delete { background-color: #e74c3c; }
.card .actions a.delete:hover { background-color: #b8362a; }

.card img.logo {
    max-height: 50px;
    border-radius: 8px;
    object-fit: contain;
    margin: auto;
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
/* Responsividade */
@media(max-width: 900px){
    .main-container {
        flex-direction: column;
        align-items: center;
    }
    .left-column, .right-column {
        width: 100%;
    }
    .right-column {
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }
}
</style>
</head>
<body>
      <header class="logo-header">
    <img src="imgs/logo.png" alt="New Football Logo" class="logo" />
  </header>

<h1>Gerenciar Jogos</h1>

<div class="main-container">
    <!-- Coluna esquerda: Formulário -->
    <div class="left-column">
        <div class="card add-card">
            <h2>Adicionar Novo Jogo</h2>
            <form method="POST" style="display:flex; flex-direction:column; gap:8px;">
                <label for="data">Data:</label>
                <input type="date" id="data" name="data" required>

                <label for="horario">Horário:</label>
                <input type="time" id="horario" name="horario" required>

                <label for="categoria">Categoria:</label>
                <input type="text" id="categoria" name="categoria" required>

                <label for="tipo">Tipo:</label>
                <select id="tipo" name="tipo" required>
                    <option value="">Selecione</option>
                    <option value="Amistoso">Amistoso</option>
                    <option value="Oficial">Oficial</option>
                </select>

                <label for="local">Local:</label>
                <input type="text" id="local" name="local" required>

                <label for="adversario">Adversário:</label>
                <input type="text" id="adversario" name="adversario" required>

                <label for="logo_url">URL Logo:</label>
                <input type="text" id="logo_url" name="logo_url" required>

                <label for="turma_id">Turma:</label>
                <select id="turma_id" name="turma_id" required>
                    <option value="">Selecione</option>
                    <?php while ($turma = $turmas_result->fetch_assoc()): ?>
                        <option value="<?= $turma['id'] ?>"><?= htmlspecialchars($turma['nome']) ?></option>
                    <?php endwhile; ?>
                </select>

                <button type="submit">Adicionar</button>
            </form>
        </div>
    </div>

    <!-- Coluna direita: Jogos cadastrados -->
    <div class="right-column">
        <?php if ($jogos->num_rows > 0): ?>
            <?php while ($j = $jogos->fetch_assoc()): ?>
                <div class="card game-card">
                    <h2><?= strtoupper($j['adversario']) ?></h2>
                    <p><strong>Data:</strong> <?= date('d/m/Y', strtotime($j['data'])) ?></p>
                    <p><strong>Horário:</strong> <?= date('H:i', strtotime($j['horario'])) ?></p>
                    <p><strong>Categoria:</strong> <?= strtoupper($j['categoria']) ?></p>
                    <p><strong>Tipo:</strong> <?= htmlspecialchars($j['tipo'] ?? '') ?></p>
                    <p><strong>Local:</strong> <?= strtoupper($j['local']) ?></p>
                    <p><strong>Turma:</strong> <?= strtoupper($j['turma_nome']) ?></p>
                    <img src="<?= htmlspecialchars($j['logo_url']) ?>" class="logo" alt="Logo do adversário">

                    <div class="actions">
                        <a href="editar_jogo.php?id=<?= $j['id'] ?>" class="edit"><i class="bi bi-pencil"></i></a>
                        <a href="?excluir=<?= $j['id'] ?>" class="delete" onclick="return confirm('Deseja excluir este jogo?')"><i class="bi bi-trash"></i></a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="color:#fff; width:100%; text-align:center;">Nenhum jogo cadastrado.</p>
        <?php endif; ?>
    </div>
</div>

<div id="nav-placeholder"></div>
<script src="js/nav_professor.js"></script>
</body>
</html>
