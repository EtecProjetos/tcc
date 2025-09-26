<?php
session_start();

// Verifica se o admin está logado
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: admin.php");
    exit;
}

// Inclui a conexão com o banco de dados
include '../Back/conexao.php';

// Pega o ID do campeonato via GET
$campeonato_id = isset($_GET['campeonato_id']) ? $_GET['campeonato_id'] : null;
if ($campeonato_id == null) {
    echo "Erro: Nenhum campeonato selecionado.";
    exit;
}

// Busca os alunos
$sql_alunos = "SELECT * FROM alunos ORDER BY nome ASC";
$alunos_result = $conn->query($sql_alunos);

// Busca os professores
$sql_professores = "SELECT * FROM professores ORDER BY nome ASC";
$professores_result = $conn->query($sql_professores);

// Caso o formulário de convocação seja enviado, processa a convocação
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $convocados_alunos = isset($_POST['alunos']) ? $_POST['alunos'] : [];
    $convocados_professores = isset($_POST['professores']) ? $_POST['professores'] : [];

    // Insere os alunos convocados
    foreach ($convocados_alunos as $aluno_id) {
        $stmt = $conn->prepare("INSERT INTO convocados_campeonatos (campeonato_id, aluno_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $campeonato_id, $aluno_id);
        $stmt->execute();
    }

    // Insere os professores convocados
    foreach ($convocados_professores as $professor_id) {
        $stmt = $conn->prepare("INSERT INTO convocados_professores (campeonato_id, professor_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $campeonato_id, $professor_id);
        $stmt->execute();
    }

    echo "<p>Convocação realizada com sucesso!</p>";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Convocar Alunos e Professores</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 10px;
        }
        .card {
            margin: 10px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .card:hover {
            background-color: #e0e0e0;
        }
        .btn-convocar {
            background-color: #390062;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-convocar:hover {
            background-color: #6e007f;
        }
        .btn-save {
            background-color: #4CAF50;
            color: white;
            padding: 15px 32px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .selected {
            background-color: green !important;
            color: white;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
        }

        .card-container .card {
            width: 200px;
            margin: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Convocar Alunos e Professores para o Campeonato</h2>

    <form method="POST">

        <h3>Alunos</h3>
        <div class="card-container">
            <?php while ($aluno = $alunos_result->fetch_assoc()) { ?>
                <div class="card">
                    <input type="checkbox" name="alunos[]" value="<?php echo $aluno['id']; ?>" id="aluno-<?php echo $aluno['id']; ?>" class="convocar-checkbox">
                    <label for="aluno-<?php echo $aluno['id']; ?>"><strong><?php echo $aluno['nome']; ?></strong></label>
                </div>
            <?php } ?>
        </div>

        <h3>Professores</h3>
        <div class="card-container">
            <?php while ($professor = $professores_result->fetch_assoc()) { ?>
                <div class="card">
                    <input type="checkbox" name="professores[]" value="<?php echo $professor['id']; ?>" id="professor-<?php echo $professor['id']; ?>" class="convocar-checkbox">
                    <label for="professor-<?php echo $professor['id']; ?>"><strong><?php echo $professor['nome']; ?></strong></label>
                </div>
            <?php } ?>
        </div>

        <button type="submit" class="btn-save">Salvar Convocação</button>
    </form>
</div>

<script>
    // Adiciona a classe 'selected' quando o checkbox é marcado
    document.querySelectorAll('.convocar-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const card = this.closest('.card');
            if (this.checked) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        });
    });
</script>

</body>
</html>
