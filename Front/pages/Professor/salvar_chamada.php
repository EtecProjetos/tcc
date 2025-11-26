<?php
session_start();
include '../../../Back/conexao.php';

if (!isset($_SESSION['professor_id'])) {
    header("Location: loginProfessor.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Método inválido.");
}

$turma_id = $_POST['turma_id'] ?? null;
$data = $_POST['data'] ?? null;
$presencas = $_POST['presencas'] ?? [];

if (!$turma_id || !$data) {
    die("Turma ou data não informada.");
}

// Busca alunos da turma
$stmt = $conn->prepare("SELECT id FROM alunos WHERE turma_id = ?");
$stmt->bind_param("i", $turma_id);
$stmt->execute();
$result = $stmt->get_result();

while ($aluno = $result->fetch_assoc()) {
    $aluno_id = $aluno['id'];
    $presente = isset($presencas[$aluno_id]) ? 1 : 0;

    // Verifica se já existe registro para o aluno nessa data
    $check = $conn->prepare("SELECT id FROM frequencia WHERE aluno_id=? AND data=?");
    $check->bind_param("is", $aluno_id, $data);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        // Se já existe, atualiza o registro
        $update = $conn->prepare("UPDATE frequencia SET presente=? WHERE aluno_id=? AND data=?");
        $update->bind_param("iis", $presente, $aluno_id, $data);
        $update->execute();
        $update->close();
    } else {
        // Se não existe, insere novo registro (treino_id como NULL)
        $insert = $conn->prepare("INSERT INTO frequencia (aluno_id, data, presente, treino_id) VALUES (?, ?, ?, NULL)");
        $insert->bind_param("isi", $aluno_id, $data, $presente);
        $insert->execute();
        $insert->close();
    }

    $check->close();
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>Chamada Salva</title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="shortcut icon" href="../../imgs/logo.png" type="image/x-icon">
<style>
body {
    margin: 0;
    font-family: 'Roboto', sans-serif;
    background: linear-gradient(to bottom, #6a0dad 0%, #000000 100%);
    color: #fff;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px 10px;
}
.card {
    background: #fff;
    color: #390062;
    padding: 30px 35px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 6px 20px rgba(111,45,168,0.4);
    max-width: 400px;
    width: 100%;
    box-sizing: border-box;
}
h2 { font-weight: 700; margin-bottom: 15px; color: #6f2da8; }
p { margin: 10px 0; }
.loading-dots { font-size: 18px; font-weight: bold; color: #390062; }
.loading-dots span { opacity: 0; animation: blink 1.4s infinite both; display: inline-block; }
.loading-dots span:nth-child(1) { animation-delay: 0s; }
.loading-dots span:nth-child(2) { animation-delay: 0.2s; }
.loading-dots span:nth-child(3) { animation-delay: 0.4s; }
@keyframes blink { 0%,20% { opacity:0; } 50% { opacity:1; } 100% { opacity:0; } }
@media (max-width: 480px) { .card { padding: 25px 20px; } h2 { font-size: 1.6rem; } .loading-dots { font-size: 16px; } }
</style>
<script>
setTimeout(()=>{ window.location.href='home_professor.php'; },3000);
</script>
</head>
<body>
<div class="card">
    <h2>Sucesso!</h2>
    <p>A chamada foi salva com sucesso.</p>
    <p class="loading-dots">Aguarde<span>.</span><span>.</span><span>.</span></p>
</div>
</body>
</html>
