<?php
session_start();

// Verifica se o admin está logado
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: admin.php");
    exit;
}

// Conecta ao banco de dados
include '../Back/conexao.php';

// Busca os professores
$sql = "SELECT * FROM professores ORDER BY nome ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Convocar Professores</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #000000, #4c0070);
            color: white;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #FFD700;
            margin-bottom: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            color: #000;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .professor-card {
            border: 2px solid #7a0ea4;
            border-radius: 10px;
            padding: 15px;
            margin: 10px;
            display: inline-block;
            width: calc(33.33% - 20px);
            box-sizing: border-box;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: #7a0ea4;
            color: white;
        }

        /* Card selecionado - a borda e fundo ficam verdes */
        .professor-card.selected {
            border-color: #32CD32;
            background-color: #32CD32;
            color: white;
        }

        .professor-card:hover {
            background-color: #9b4fdd;
            transform: scale(1.05);
        }

        .search-container {
            margin-bottom: 20px;
            text-align: center;
        }

        .search-container input {
            padding: 10px;
            font-size: 16px;
            width: 80%;
            max-width: 400px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .btn-convocar {
            background-color: #390062;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
            display: block;
            margin: 20px auto;
        }

        .btn-convocar:hover {
            background-color: #6e007f;
        }

        .btn-convocar:focus {
            outline: none;
        }

        .btn-voltar {
            background-color: #FFD700;
            color: #000;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
            margin-bottom: 20px;
            display: inline-block;
        }

        .btn-voltar:hover {
            background-color: #c1a800;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
        }

        #professores-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .btn-convocar-aluno {
            background-color: #007fff;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
            display: block;
            margin: 20px auto;
        }

        .btn-convocar-aluno:hover {
            background-color: #005cbf;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Convocação de Professores</h1>

    <!-- Barra de Pesquisa -->
    <div class="search-container">
        <input type="text" id="search" placeholder="Pesquisar Professores..." onkeyup="filterProfessores()">
    </div>

    <div class="btn-container">
        <!-- Botão de Voltar -->
        <button class="btn-voltar" onclick="location.href='home_admin.php'">Voltar</button>
    </div>

    <div id="professores-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $nome = htmlspecialchars($row['nome']);
                $especialidade = isset($row['especialidade']) ? htmlspecialchars($row['especialidade']) : 'Especialidade não disponível';
                $idade = isset($row['data_nascimento']) ? date_diff(date_create($row['data_nascimento']), date_create('today'))->y : 'Idade não disponível';
                echo '<div class="professor-card" data-id="' . $row['id'] . '" data-nome="' . $nome . '">';
                echo '<p><strong>Nome:</strong> ' . $nome . '</p>';
                echo '<p><strong>Idade:</strong> ' . $idade . '</p>';
              
                echo '</div>';
            }
        } else {
            echo "<p>Nenhum professor encontrado.</p>";
        }
        ?>
    </div>

    <!-- Botão de Salvar Convocação -->
    <button class="btn-convocar" id="save-button" onclick="saveConvocacao()">Salvar Convocação</button>

    <!-- Botão de Convocar Aluno -->
    <button class="btn-convocar-aluno" onclick="location.href='buscar_aluno.php'">Convocar Aluno</button>

</div>

<script>
    // Função para filtrar os professores na busca
    function filterProfessores() {
        const search = document.getElementById('search').value.toLowerCase();
        const professores = document.querySelectorAll('.professor-card');

        professores.forEach(professor => {
            const nome = professor.dataset.nome.toLowerCase();
            if (nome.includes(search)) {
                professor.style.display = 'inline-block';
            } else {
                professor.style.display = 'none';
            }
        });
    }

    // Marcar o professor como selecionado ao clicar
    const professores = document.querySelectorAll('.professor-card');
    professores.forEach(professor => {
        professor.addEventListener('click', () => {
            professor.classList.toggle('selected');
        });
    });

    // Função para salvar a convocação
    function saveConvocacao() {
        const selecionados = document.querySelectorAll('.professor-card.selected');
        const idsSelecionados = Array.from(selecionados).map(card => card.dataset.id);
        
        if (idsSelecionados.length > 0) {
            // Enviar os dados dos professores selecionados para salvar a convocação
            alert('Convocação salva para os professores com ID: ' + idsSelecionados.join(', '));
            // Aqui você pode adicionar a lógica para salvar os dados no banco, utilizando AJAX ou PHP.
        } else {
            alert('Nenhum professor selecionado!');
        }
    }
</script>

</body>
</html>
