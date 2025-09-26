<?php
session_start();

// Verifica se o admin está logado
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: admin.php");
    exit;
}

// Conecta ao banco de dados
include '../Back/conexao.php';

// Busca os campeonatos
$sql = "SELECT * FROM campeonatos ORDER BY data ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Convocação para Campeonatos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #000000, #4c0070); /* Degradê roxo */
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            padding: 0 15px;
        }

        h1 {
            margin-bottom: 30px;
            font-size: 2.5rem;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            color: #FFD700;
            text-shadow: 2px 2px 5px rgba(0,0,0,0.5);
            width: 100%;
        }

        .container {
            max-width: 1200px; /* Aumentado para maior largura */
            margin: 0 auto;
            padding: 20px;
            background: none;
            color: #fff;
            border-radius: 10px;
            box-shadow: none;
            display: flex;
            flex-wrap: wrap; /* Permite quebra de linha após 3 cards */
            justify-content: space-evenly; /* Espaça os cards de forma equilibrada */
            gap: 20px; /* Espaçamento entre os cards */
        }

        .campeonato-card {
            border: 1px solid #7a0ea4; /* Roxo mais forte */
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            background: linear-gradient(135deg, #7a0ea4, #a020f0); /* Degradê roxo */
            color: white;
            transition: transform 0.3s, background-color 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 280px; /* Tamanho fixo para card */
            min-width: 280px;
            height: 220px; /* Tamanho fixo para card */
            text-align: center;
        }

        .campeonato-card:hover {
            transform: scale(1.05);
            background: linear-gradient(135deg, #a020f0, #6e007f); /* Cor mais clara ao passar o mouse */
        }

        .btn-convocar {
            background-color: #390062;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
            font-size: 1rem;
        }

        .btn-convocar:hover {
            background-color: #6e007f;
        }

        .btn-convocar:focus {
            outline: none;
        }

        .no-campeonatos {
            text-align: center;
            font-size: 18px;
            color: #fff;
        }
    </style>
</head>
<body>

<h1>Convocação para Campeonatos</h1>

<div class="container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="campeonato-card">';
            echo '<h3>' . htmlspecialchars($row['nome']) . '</h3>';
            echo '<p><strong>Data:</strong> ' . date("d/m/Y", strtotime($row['data'])) . '</p>';
            echo '<button class="btn-convocar" onclick="location.href=\'buscar_aluno.php?campeonato_id=' . $row['id'] . '\'">Convocar</button>';
            echo '</div>';
        }
    } else {
        echo "<p class='no-campeonatos'>Nenhum campeonato cadastrado.</p>";
    }
    ?>

</div>

</body>
</html>
