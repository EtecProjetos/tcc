<?php
include '../back/conexao.php';
session_start();

$professor_id = $_SESSION['professor_id'] ?? 1;

$stmt = $conn->prepare("SELECT id, nome FROM turmas");
$stmt->execute();
$result = $stmt->get_result();
$turmas = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Iniciar Chamada</title>
<link rel="stylesheet" href="styleBase.css">
<style>
body {
    font-family: 'Fredoka', sans-serif;
    background-color: #520c6f;
    color: #fff;
    margin: 0;
    padding: 20px 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}
.container {
    background: linear-gradient(to bottom, #5e2ca5, #7e57c2);
    width: 100%;
    max-width: 480px;
    border-radius: 16px;
    padding: 30px 25px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.4);
}
h2 {
    text-align: center;
    margin-bottom: 30px;
}
form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}
label {
    font-weight: 600;
}
select, input[type="date"] {
    padding: 12px 15px;
    border-radius: 8px;
    border: 2px solid #fff900;
    font-size: 1rem;
    color: #390062;
}
select:focus, input[type="date"]:focus {
    outline: none;
    box-shadow: 0 0 5px #fff900aa;
}
.btn, .btn-voltar {
    padding: 14px;
    font-size: 1.1rem;
    font-weight: 700;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}
.btn {
    background-color: #fff900;
    color: #390062;
}
.btn:hover { background-color: #ffd700; }
.btn-voltar {
    background-color: #ccc;
    color: #390062;
    margin-bottom: 20px;
}
.btn-voltar:hover { background-color: #bbb; }
@media (max-width: 400px) {
    .container { padding: 20px 15px; }
}
</style>
</head>
<body>
<div class="container">
    <a href="home_professor.php" class="btn-voltar">← Voltar</a>
    <h2>Iniciar Chamada</h2>
    <form action="chamada.php" method="GET" autocomplete="off">
        <label for="turma_id">Turma:</label>
        <select name="turma_id" id="turma_id" required>
            <option value="" disabled selected>Selecione...</option>
            <?php foreach ($turmas as $turma): ?>
                <option value="<?= $turma['id'] ?>"><?= htmlspecialchars($turma['nome']) ?></option>
            <?php endforeach; ?>
        </select>
        <label for="data">Data da Aula:</label>
        <input type="date" name="data" id="data" required>
        <button class="btn" type="submit">Continuar</button>
    </form>
</div>
</body>
</html>
