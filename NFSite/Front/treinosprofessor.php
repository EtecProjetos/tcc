<?php
include '../back/conexao.php';
session_start();

// Verifica se o professor está logado
if (!isset($_SESSION['professor_id'])) {
    header("Location: loginProfessor.php");
    exit();
}

$professor_id = $_SESSION['professor_id'];

// Inserção de novo treino
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data'], $_POST['horario'], $_POST['turma_id'])) {
    $data = $_POST['data'];
    $horario = $_POST['horario'];
    $turma_id = intval($_POST['turma_id']);

    $stmt = $conn->prepare("INSERT INTO treinos (data, horario, turma_id, professor_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $data, $horario, $turma_id, $professor_id);
    $stmt->execute();
    $stmt->close();

    header("Location: treinosprofessor.php");
    exit();
}

// Exclusão de treino
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    $conn->query("DELETE FROM treinos WHERE id = $id AND professor_id = $professor_id");
    header("Location: treinosprofessor.php");
    exit();
}

// Buscar apenas turmas do professor logado
$t_result = $conn->query("SELECT id, nome FROM turmas WHERE professor_id = $professor_id");

// Buscar apenas treinos do professor logado
$sql = "
SELECT t.id, t.data, t.horario, tur.nome AS turma_nome
FROM treinos t
JOIN turmas tur ON t.turma_id = tur.id
WHERE t.professor_id = $professor_id
ORDER BY t.data DESC, t.horario DESC
";
$treinos = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Gerenciar Treinos - Professor</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />
  
  <style>
    body {
      margin: 0;
      background-color: #520c6f;
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
      max-width: 720px;
      width: 100%;
      padding: 30px 35px;
      box-shadow: 0 4px 20px rgba(111, 45, 168, 0.3);
      color: #4b0082;
      box-sizing: border-box;
    }

    h1 {
      text-align: center;
      font-weight: 700;
      font-size: 2rem;
      margin-bottom: 30px;
      color: #6f2da8;
    }

    form {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-bottom: 40px;
      justify-content: space-between;
    }
    form label {
      flex: 1 0 100%;
      font-weight: 500;
      margin-bottom: 8px;
      color: #4b0082;
    }
    form input[type="date"],
    form input[type="time"],
    form select {
      flex: 1 1 calc(33% - 15px);
      padding: 14px 16px;
      font-size: 16px;
      border: 2px solid #6f2da8;
      border-radius: 14px;
      color: #4b0082;
      transition: border-color 0.3s ease;
      outline-offset: 2px;
    }
    form input[type="date"]:focus,
    form input[type="time"]:focus,
    form select:focus {
      border-color: #390062;
      outline: none;
    }
    form button {
      flex: 1 0 100%;
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
    form button:hover {
      background-color: #551b9a;
    }

    /* Tabela */
    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0 14px;
      box-sizing: border-box;
    }
    thead {
      background-color: #6f2da8;
      color: white;
      font-weight: 700;
      position: sticky;
      top: 0;
      z-index: 2;
      display: table-header-group;
    }
    th, td {
      padding: 16px 14px;
      text-align: center;
      font-weight: 600;
      background-color: #fdf7ff;
      color: #4b0082;
      box-shadow: 0 3px 8px rgba(111, 45, 168, 0.12);
      vertical-align: middle;
      min-width: 120px;
    }
    th {
      border-radius: 10px 10px 0 0;
    }
    tbody tr {
      background: #fdf7ff;
      border-radius: 16px;
      box-shadow: 0 3px 8px rgba(111, 45, 168, 0.1);
    }
    tbody td {
      border-bottom: 14px solid transparent;
    }

    td:last-child {
      display: flex;
      justify-content: center;
      gap: 12px;
      flex-wrap: wrap;
    }
    a.btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 14px;
      border-radius: 12px;
      font-weight: 700;
      font-size: 14px;
      color: white;
      text-decoration: none;
      user-select: none;
      transition: background-color 0.3s ease;
      box-shadow: 0 3px 8px rgba(0,0,0,0.15);
      min-width: 90px;
      justify-content: center;
    }
    a.btn-edit {
      background: #2980b9;
    }
    a.btn-edit:hover {
      background: #1c5980;
    }
    a.btn-delete {
      background: #e74c3c;
    }
    a.btn-delete:hover {
      background: #b8362a;
    }

    .empty {
      text-align: center;
      color: #aaa;
      font-size: 1.3rem;
      padding: 40px 0;
    }

    /* Responsividade */
    @media (max-width: 720px) {
      form input[type="date"],
      form input[type="time"],
      form select {
        flex: 1 0 100%;
      }
      th, td {
        min-width: 90px;
        padding: 12px 8px;
        font-size: 14px;
      }
      a.btn {
        font-size: 13px;
        padding: 7px 12px;
        min-width: 80px;
      }
    }

    @media (max-width: 480px) {
      /* Form ajustado para mobile */
      form {
        gap: 15px;
      }
      form button {
        padding: 14px 0;
        font-size: 1rem;
      }

      /* Tabela modo lista */
      table, thead, tbody, th, td, tr {
        display: block;
      }
      thead tr {
        display: none;
      }
      tbody tr {
        margin-bottom: 15px;
        background: #fdf7ff;
        border-radius: 16px;
        padding: 15px 20px;
        box-shadow: 0 3px 12px rgba(111, 45, 168, 0.15);
      }
      tbody td {
        padding: 8px 8px 8px 110px;
        position: relative;
        text-align: left;
        border: none;
        box-shadow: none;
        font-weight: 500;
        word-wrap: break-word;
        white-space: normal;
      }
      tbody td:last-child {
        padding-left: 0;
        display: flex;
        justify-content: flex-start;
        gap: 12px;
        margin-top: 10px;
        flex-wrap: nowrap;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }
      tbody td::before {
        content: attr(data-label);
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        font-weight: 700;
        color: #6f2da8;
        white-space: nowrap;
      }
    }
  </style>
</head>
<body>

<header class="logo-header">
  <img src="imgs/logo.png" alt="New Football Logo" class="logo" />
</header>

<div class="container">
  <h1>Gerenciar Treinos</h1>

  <form method="POST" aria-label="Formulário para adicionar novo treino">
    <label for="data">Data do Treino:</label>
    <input id="data" type="date" name="data" required />

    <label for="horario">Horário:</label>
    <input id="horario" type="time" name="horario" required />

    <label for="turma_id">Turma:</label>
    <select id="turma_id" name="turma_id" required>
      <option value="">Selecione a turma</option>
      <?php while ($turma = $t_result->fetch_assoc()): ?>
        <option value="<?= $turma['id'] ?>"><?= htmlspecialchars($turma['nome']) ?></option>
      <?php endwhile; ?>
    </select>

    <button type="submit">Adicionar Treino</button>
  </form>

  <?php if ($treinos->num_rows > 0): ?>
    <table role="table" aria-label="Tabela de treinos cadastrados">
      <thead>
        <tr>
          <th scope="col">Data</th>
          <th scope="col">Horário</th>
          <th scope="col">Turma</th>
          <th scope="col">Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($t = $treinos->fetch_assoc()): ?>
          <tr>
            <td data-label="Data"><?= date('d/m/Y', strtotime($t['data'])) ?></td>
            <td data-label="Horário"><?= date('H:i', strtotime($t['horario'])) ?></td>
            <td data-label="Turma"><?= htmlspecialchars($t['turma_nome']) ?></td>
            <td>
              <a href="editar_treino.php?id=<?= $t['id'] ?>" class="btn btn-edit" aria-label="Editar treino"><i class="bi bi-pencil-square"></i></a>
              <a href="?excluir=<?= $t['id'] ?>" class="btn btn-delete" onclick="return confirm('Tem certeza que deseja excluir este treino?')" aria-label="Excluir treino"><i class="bi bi-trash"></i></a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p class="empty" role="alert">Nenhum treino cadastrado</p>
  <?php endif; ?>
</div>

<div id="nav-placeholder"></div>
<script src="js/nav_professor.js"></script>
</body>
</html>
