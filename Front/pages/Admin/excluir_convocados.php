<?php
session_start();

if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: admin.php");
    exit;
}

include '../../../back/conexao.php';

$aluno_id = $_POST['aluno_id'] ?? null;
$campeonato_id = $_POST['campeonato_id'] ?? null;

if ($aluno_id && $campeonato_id) {
    $stmt = $conn->prepare("DELETE FROM convocacoes WHERE aluno_id = ? AND campeonato_id = ?");
    $stmt->bind_param("ii", $aluno_id, $campeonato_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: listar_convocados.php?campeonato_id=".$campeonato_id);
exit;
