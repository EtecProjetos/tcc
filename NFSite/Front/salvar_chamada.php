<?php
include '../back/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $turma_id = $_POST['turma_id'];
    $data = $_POST['data'];
    $presencas = $_POST['presencas'] ?? [];

    // Salva as presenças
    $stmt = $conn->prepare("SELECT id FROM alunos WHERE turma_id = ?");
    $stmt->bind_param("i", $turma_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($aluno = $result->fetch_assoc()) {
        $aluno_id = $aluno['id'];
        $presente = isset($presencas[$aluno_id]) ? 1 : 0;

        // Evita duplicação
        $check = $conn->prepare("SELECT id FROM frequencias WHERE aluno_id = ? AND turma_id = ? AND data = ?");
        $check->bind_param("iis", $aluno_id, $turma_id, $data);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows == 0) {
            $insert = $conn->prepare("INSERT INTO frequencias (aluno_id, turma_id, data, presente) VALUES (?, ?, ?, ?)");
            $insert->bind_param("iisi", $aluno_id, $turma_id, $data, $presente);
            $insert->execute();
        }
    }
} else {
    echo "Método inválido.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Chamada Salva</title>
    <style>
        body {
            background-color: #390062;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
            color: #fff;
        }
        .card {
            background: #fff;
            color: #390062;
            padding: 30px 40px;
            border-radius: 20px;
            box-shadow: 0 6px 15px rgba(57,0,98,0.4);
            text-align: center;
            max-width: 400px;
        }
        .card h2 {
            margin-bottom: 15px;
        }
        .card p {
            font-size: 18px;
            margin-bottom: 0;
        }
         .loading-dots {
        font-size: 18px;
        margin-top: 5px;
        color: #390062;
        font-weight: bold;
    }
    .loading-dots span {
        opacity: 0;
        animation-name: blink;
        animation-duration: 1.4s;
        animation-iteration-count: infinite;
        animation-fill-mode: both;
        display: inline-block;
    }
    .loading-dots span:nth-child(1) {
        animation-delay: 0s;
    }
    .loading-dots span:nth-child(2) {
        animation-delay: 0.2s;
    }
    .loading-dots span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes blink {
        0%, 20% {
            opacity: 0;
        }
        50% {
            opacity: 1;
        }
        100% {
            opacity: 0;
        }
    }
    </style>
    <script>
        // Redireciona após 3 segundos
        setTimeout(() => {
            window.location.href = 'chamada_iniciar.php';
        }, 3000);
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
