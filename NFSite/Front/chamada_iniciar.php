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
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="shortcut icon" href="imgs/logo.png" type="image/x-icon">
<style>
body {
    margin: 0;
    font-family: 'Roboto', Arial, sans-serif;
    background: linear-gradient(to bottom, #6a0dad 0%, #000000 100%);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px 10px;
    color: #4b0082;
}

.container {
    background: #fff;
    border-radius: 16px;
    max-width: 500px;
    width: 100%;
    padding: 30px 35px;
    box-shadow: 0 4px 20px rgba(111, 45, 168, 0.3);
    color: #4b0082;
    box-sizing: border-box;
}

h2 {
    text-align: center;
    font-weight: 700;
    margin-bottom: 25px;
    color: #6f2da8;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    font-weight: 500;
    color: #4b0082;
    margin-top: 20px;
}

select, input[type="date"] {
    padding: 14px 16px;
    font-size: 16px;
    border: 2px solid #6f2da8;
    border-radius: 14px;
    color: #4b0082;
    margin-top: 8px;
    outline-offset: 2px;
}

select:focus, input[type="date"]:focus {
    border-color: #390062;
    outline: none;
    box-shadow: 0 0 5px #390062aa;
}

button, .btn-voltar {
    padding: 14px 0;
    font-size: 1.2rem;
    font-weight: 700;
    border-radius: 25px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
    width: 100%;
    margin-top: 25px;
}

button {
    background-color: #ffd700;
    color: #4b0082;
}

button:hover {
    background-color: #ffe345;
}

.btn-voltar {

    color: #4b0082;
    text-decoration: none;
    text-align: center;
}

.btn-voltar:hover {
    color: #000000;
}

@media (max-width: 480px) {
    .container {
        padding: 20px;
    }
    h2 {
        font-size: 1.6rem;
    }
    select, input[type="date"], button, .btn-voltar {
        font-size: 14px;
    }
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

        <button type="submit">Continuar</button>
    </form>
</div>
</body>
</html>
