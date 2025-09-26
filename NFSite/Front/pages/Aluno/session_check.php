<?php
session_start();
include '../back/conexao.php';

if (!isset($_SESSION['aluno_id'])) {
    header("Location: loginAluno.php");
    exit;
}

$aluno_id = $_SESSION['aluno_id'];

$sql = "SELECT nome FROM alunos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $aluno_id);
$stmt->execute();
$result = $stmt->get_result();
$aluno = $result->fetch_assoc();

if (!$aluno) {
    // Se não encontrar o aluno, encerra a sessão
    session_destroy();
    header("Location: loginAluno.php");
    exit;
}
?>
