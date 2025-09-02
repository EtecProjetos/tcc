<?php
include '../back/conexao.php';



if (!isset($_GET['id'])) {
    header("Location: treinosprofessor.php");
    exit();
}

$treino_id = $_GET['id'];

$stmt = $conn->prepare("
    SELECT t.*, tur.nome AS turma_nome 
    FROM treinos t 
    JOIN turmas tur ON t.turma_id = tur.id
    WHERE t.id = ? AND t.professor_id = ?
");
$stmt->bind_param("ii", $treino_id, $professor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Treino não encontrado ou você não tem permissão.";
    exit();
}

$treino = $result->fetch_assoc();
$stmt->close();

$turmas = $conn->query("SELECT id, nome FROM turmas");

$alert = null; // 'success' ou 'nochange' ou null
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'];
    $horario = $_POST['horario'];
    $turma_id = $_POST['turma_id'];

    if ($data === $treino['data'] && $horario === $treino['horario'] && $turma_id == $treino['turma_id']) {
        $alert = 'nochange';
        $message = 'Nenhuma alteração detectada.';
    } else {
        $stmt = $conn->prepare("UPDATE treinos SET data = ?, horario = ?, turma_id = ? WHERE id = ? AND professor_id = ?");
        $stmt->bind_param("ssiii", $data, $horario, $turma_id, $treino_id, $professor_id);
        $stmt->execute();
        $stmt->close();

        $alert = 'success';
        $message = 'Treino atualizado com sucesso!';

        $treino['data'] = $data;
        $treino['horario'] = $horario;
        $treino['turma_id'] = $turma_id;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Editar Treino - Professor</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    body {
      margin: 0;
      background-color: #390062;
      font-family: 'Roboto', Arial, sans-serif;
      color: #4b0082;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 20px 10px;
    }
    header.logo-header {
      margin-bottom: 30px;
    }
    header.logo-header .logo {
      width: 180px;
      height: auto;
      display: block;
      margin: 0 auto;
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
      font-size: 2rem;
      margin-bottom: 30px;
      color: #6f2da8;
    }
    form {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }
    label {
      font-weight: 500;
      color: #4b0082;
    }
    input[type="date"],
    input[type="time"],
    select {
      padding: 14px 16px;
      font-size: 16px;
      border: 2px solid #6f2da8;
      border-radius: 14px;
      color: #4b0082;
      transition: border-color 0.3s ease;
      outline-offset: 2px;
    }
    input[type="date"]:focus,
    input[type="time"]:focus,
    select:focus {
      border-color: #390062;
      outline: none;
    }
    button {
      padding: 18px 0;
      font-size: 1.2rem;
      font-weight: 700;
      background-color: #6f2da8;
      color: white;
      border: none;
      border-radius: 20px;
      cursor: pointer;
      box-shadow: 0 4px 15px rgba(111, 45, 168, 0.5);
      transition: background-color 0.3s ease;
    }
    button:hover {
      background-color: #551b9a;
    }
    .back-link {
      display: inline-block;
      margin-top: 25px;
      text-decoration: none;
      color: #6f2da8;
      font-weight: 700;
      text-align: center;
      width: 100%;
      user-select: none;
      transition: color 0.3s ease;
    }
    .back-link:hover {
      color: #390062;
    }
    #alert-box {
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      padding: 15px 25px;
      border-radius: 20px;
      font-weight: 700;
      font-size: 1rem;
      color: white;
      z-index: 1000;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.3s ease;
      max-width: 90%;
      text-align: center;
      box-shadow: 0 4px 15px rgba(111, 45, 168, 0.5);
    }
    #alert-box.show {
      opacity: 1;
      pointer-events: auto;
    }
    #alert-box.success {
      background-color: #28a745;
    }
    #alert-box.nochange {
      background-color: #ffc107;
      color: #333;
    }
    @media (max-width: 480px) {
      .container {
        padding: 20px;
      }
      h2 {
        font-size: 1.6rem;
      }
      input[type="date"],
      input[type="time"],
      select,
      button {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>

<header class="logo-header">
  <img src="imgs/logo.png" alt="New Football Logo" class="logo" />
</header>

<div class="container">
  <h2>Editar Treino</h2>

  <form id="editForm" method="POST" aria-label="Formulário para editar treino">
    <label for="data">Data:</label>
    <input id="data" type="date" name="data" required value="<?= htmlspecialchars($treino['data']) ?>">

    <label for="horario">Horário:</label>
    <input id="horario" type="time" name="horario" required value="<?= htmlspecialchars($treino['horario']) ?>">

    <label for="turma_id">Turma:</label>
    <select id="turma_id" name="turma_id" required>
      <option value="">Selecione a turma</option>
      <?php while ($turma = $turmas->fetch_assoc()): ?>
        <option value="<?= $turma['id'] ?>" <?= $turma['id'] == $treino['turma_id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($turma['nome']) ?>
        </option>
      <?php endwhile; ?>
    </select>

    <button type="submit">Salvar Alterações</button>
  </form>

  <a href="treinosprofessor.php" class="back-link">← Voltar para Treinos</a>
</div>

<div id="alert-box"></div>

<script>
  const alertBox = document.getElementById('alert-box');

function showAlert(type, message) {
  alertBox.textContent = message;
  alertBox.className = '';
  alertBox.classList.add(type);
  alertBox.classList.add('show');

  if(type === 'success') {
    setTimeout(() => {
      window.location.href = 'treinosprofessor.php';
    }, 1500); // reduzido para 1.5 segundos
  } else {
    setTimeout(() => {
      alertBox.classList.remove('show');
    }, 3000);
  }
}

  <?php if ($alert !== null): ?>
    showAlert('<?= $alert ?>', '<?= addslashes($message) ?>');
  <?php endif; ?>
</script>

</body>
</html>
