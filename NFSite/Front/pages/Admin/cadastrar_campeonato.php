<?php
session_start();

// Verifica se o admin está logado
if(!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true){
    header("Location: admin.php");
    exit;
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conecta ao banco de dados
    include '../Back/conexao.php';

    // Captura os dados do formulário
    $nome = $_POST['nome'];
    $idade_maxima = $_POST['idade_maxima'];
    $idade_minima = isset($_POST['idade_minima']) ? $_POST['idade_minima'] : null; // Caso o campo seja opcional
    $data = $_POST['data'];

    // Verificar se a data foi enviada corretamente
    $data_formatada = date('Y-m-d', strtotime($data));

    // Verifica se a data foi convertida corretamente
    if ($data_formatada === false) {
        echo "<p>Erro: A data fornecida não é válida.</p>";
    } else {
        // Preparar o SQL para inserir no banco
        $stmt = $conn->prepare("INSERT INTO campeonatos (nome, idade_maxima, idade_minima, data) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siis", $nome, $idade_maxima, $idade_minima, $data_formatada);
        
        if ($stmt->execute()) {
            echo "<p>Campeonato cadastrado com sucesso!</p>";
        } else {
            echo "<p>Erro ao cadastrar campeonato. Tente novamente.</p>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Cadastrar Campeonato - New Football</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
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

.form-container {
    background: #fff;
    color: #333;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    width: 100%;
    max-width: 600px;
}

.form-container form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.form-container label {
    font-weight: bold;
}

.form-container input[type="text"],
.form-container input[type="number"],
.form-container input[type="date"] {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 1rem;
    width: 100%;
    background-color: #f9f9f9;
}

.form-container input[type="submit"] {
    background: linear-gradient(135deg, #7a0ea4, #a020f0);
    color: white;
    border: none;
    padding: 12px;
    border-radius: 10px;
    font-size: 1.2rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.form-container input[type="submit"]:hover {
    background: linear-gradient(135deg, #a020f0, #b356f5);
    transform: scale(1.05);
}

button {
    background-color: #390062;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 1.2rem;
    transition: background 0.3s ease;
}

button:hover {
    background-color: #7a0ea4;
}
</style>
</head>
<body>

<h1>Cadastrar Campeonato</h1>

<div class="form-container">
    <form action="cadastrar_campeonato.php" method="POST">
        <label for="nome">Nome do Campeonato:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="idade_maxima">Idade Máxima:</label>
        <input type="number" id="idade_maxima" name="idade_maxima" required>

        <label for="idade_minima">Idade Mínima:</label>
        <input type="number" id="idade_minima" name="idade_minima">

        <label for="data">Data do Campeonato:</label>
        <input type="date" id="data" name="data" required>

        <input type="submit" value="Cadastrar Campeonato">
    </form>

    <button onclick="location.href='home_admin.php'">Voltar</button>
</div>

</body>
</html>
