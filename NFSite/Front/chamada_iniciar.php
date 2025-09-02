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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Iniciar Chamada</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #390062;
            color: #fff;
            margin: 0;
            padding: 20px 10px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: #fff;
            color: #333;
            width: 100%;
            max-width: 480px;
            border-radius: 16px;
            padding: 30px 25px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.3);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
            color: #390062;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        label {
            font-weight: 600;
            margin-bottom: 6px;
            font-size: 1rem;
        }
        select, input[type="date"] {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1.5px solid #ccc;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            width: 100%;
            color: #333;
        }
        select:focus, input[type="date"]:focus {
            outline: none;
            border-color: #390062;
            box-shadow: 0 0 5px #390062aa;
        }
        .btn {
            background-color: #390062;
            color: white;
            padding: 14px;
            font-size: 1.1rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            transition: background-color 0.3s ease;
            width: 100%;
        }
        .btn:hover,
        .btn:focus {
            background-color: #5a008a;
            outline: none;
        }
        /* Botão voltar */
        .btn-voltar {
            background-color: #ccc;
            color: #390062;
            padding: 10px 14px;
            font-size: 1rem;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            margin-bottom: 20px;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        .btn-voltar:hover,
        .btn-voltar:focus {
            background-color: #bbb;
            outline: none;
        }
        @media (max-width: 400px) {
            .container {
                padding: 20px 15px;
            }
            h2 {
                font-size: 1.5rem;
            }
            select, input[type="date"] {
                font-size: 0.95rem;
            }
            .btn {
                font-size: 1rem;
                padding: 12px;
            }
            .btn-voltar {
                font-size: 0.95rem;
                padding: 10px;
            }
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

        <button class="btn" type="submit">Continuar</button>
    </form>
    <br>
    <a href="home_professor.php">    <button class="btn-voltar">← Voltar</button></a>
</div>
</body>
</html>
