<?php
include '../../../back/conexao.php';
session_start();

$professor_id = $_SESSION['professor_id'] ?? 1;

// Busca turmas
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
<link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">
<style>
body { 
    font-family: 'Roboto', sans-serif; 
    background: linear-gradient(to bottom, #6a0dad 0%, #000000 100%); 
    margin:0; 
    display:flex; 
    justify-content:center; 
    align-items:center; 
    min-height:100vh; 
    color:#4b0082; 
}
.container { 
    background:#fff; 
    border-radius:16px; 
    max-width:500px; 
    width:100%; 
    padding:30px 35px; 
    box-shadow:0 4px 20px rgba(111,45,168,0.3); 
}
h2 { 
    text-align:center; 
    color:#6f2da8; 
    margin-bottom:25px; 
}
label { 
    margin-top:20px; 
    display:block; 
    font-weight:500; 
}

/* ===== Uniformiza select e input date ===== */
select, input[type="date"] {
    width: 100%;
    padding: 12px;
    margin-top: 8px;
    border-radius: 12px;
    border: 2px solid #6f2da8;
    outline: none;
    font-size: 1rem;      /* garante mesma altura de texto */
    box-sizing: border-box; /* garante padding e border dentro da largura */
    height: 46px;          /* altura uniforme */
}

.buttoniniciar { 
    margin-top:25px; 
    padding:14px 0; 
    width:100%; 
    border:none; 
    border-radius:25px; 
    font-weight:700; 
    cursor:pointer; 
    background:#ffd700; 
    color:#4b0082; 
    font-size:1.1rem; 
}
.buttoniniciar:hover { 
    background:#ffe345; 
}
</style>
</head>
<body>
<div class="container">
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

    <button type="submit" class="buttoniniciar">Continuar</button>
</form>
</div>
</body>
</html>
<?php include './nav_professor.php'; ?>
<script src="../../js/nav_professor.js"></script>
