<?php
session_start();
include '../back/conexao.php';

// Verifica se aluno está logado
if (!isset($_SESSION['aluno_id'])) {
    header("Location: loginAluno.php");
    exit();
}

$aluno_id = $_SESSION['aluno_id'];

// Pega a turma do aluno
$sql_turma = "SELECT turma_id FROM alunos WHERE id = ?";
$stmt = $conn->prepare($sql_turma);
$stmt->bind_param("i", $aluno_id);
$stmt->execute();
$res_turma = $stmt->get_result();
$turma_id = ($res_turma->num_rows > 0) ? $res_turma->fetch_assoc()['turma_id'] : null;
$stmt->close();

$jogos = [];
if ($turma_id) {
    $sql_jogos = "
        SELECT * FROM jogos 
        WHERE turma_id = ? AND data >= CURDATE()
        ORDER BY data, horario
    ";
    $stmt = $conn->prepare($sql_jogos);
    $stmt->bind_param("i", $turma_id);
    $stmt->execute();
    $res_jogos = $stmt->get_result();
    while ($row = $res_jogos->fetch_assoc()) {
        $jogos[] = $row;
    }
    $stmt->close();
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Jogos do Aluno</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style_jogos.css" />
<link rel="stylesheet" href="styleBase.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #520c6f;
    color: white;
    padding-bottom: 80px;
}

.card-jogo {
    background-color: #7a0ea4;
    margin: 20px;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
    text-align: center;
}

.topo {
    background: linear-gradient(to right, #2b003c, #3f0058);
    padding: 10px;
}

.logo {
    width: 90px;
    height: 90px;
    object-fit: contain;
    border-radius: 50%;
    margin-top: 5px;
}

.time-nome {
    margin-top: 5px;
    font-weight: bold;
    font-size: 1rem;
    display: block;
    color: #fff;
}

.versus {
    display: flex;
    justify-content: space-around;
    align-items: center;
    margin-top: 15px;
    font-weight: bold;
    font-size: 1.2em;
    gap: 20px;
}

.logo-time,
.logo {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
}

.bi-x-lg {
    font-size: 36px;
    color: white;
}

@media (max-width: 600px) {
    .versus {
        font-size: 1em;
        gap: 12px;
    }
    .logo-time,
    .logo {
        width: 60px;
        height: 60px;
    }
    .bi-x-lg {
        font-size: 28px;
    }
}

.info {
    background-color: #300043;
    padding: 12px 15px;
    font-weight: bold;
    font-size: 1em;
    display: flex;
    align-items: center;
    gap: 8px;
    justify-content: center;
}
</style>
</head>
<body>

<?php if (count($jogos) > 0): ?>
    <?php foreach ($jogos as $jogo): ?>
        <div class="card-jogo">
            <div class="topo">
                <img src="imgs/logo.png" alt="New Football Logo" class="logo" />
                <span class="time-nome">NEW FOOTBALL</span>
            </div>

            <div class="versus">
                <div class="time-bloco">
                    <img src="<?= htmlspecialchars($jogo['logo_url']) ?>" alt="Logo Adversário" class="logo-time" />
                    <span class="time-nome"><?= mb_strtoupper(htmlspecialchars($jogo['adversario']), 'UTF-8') ?></span>
                </div>
                <i class="bi bi-x-lg"></i>
                <div class="time-bloco">
                    <img src="imgs/logo.png" alt="New Football Logo" class="logo" />
                    <span class="time-nome">NEW FOOTBALL</span>
                </div>
            </div>

            <div class="info"><i class="bi bi-house"></i> LOCAL: <?= mb_strtoupper(htmlspecialchars($jogo['local']), 'UTF-8') ?></div>
            <div class="info"><i class="bi bi-clock"></i> HORÁRIO: <?= date("H:i", strtotime($jogo['horario'])) ?></div>
            <div class="info"><i class="bi bi-calendar-event"></i> DATA: <?= date('d/m/Y', strtotime($jogo['data'])) ?></div>
            <div class="info"><i class="bi bi-list"></i> CATEGORIA: <?= mb_strtoupper(htmlspecialchars($jogo['categoria']), 'UTF-8') ?></div>
            <div class="info"><i class="bi bi-person-lines-fill"></i> ADVERSÁRIO: <?= mb_strtoupper(htmlspecialchars($jogo['adversario']), 'UTF-8') ?></div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p style="text-align:center; color:white;">NENHUM JOGO DISPONÍVEL PARA SUA TURMA.</p>
<?php endif; ?>
  <div id="nav-placeholder"></div>

  <!-- Script externo para controle da navbar -->
  <script src="js/nav.js"></script>
</body>
</html>
