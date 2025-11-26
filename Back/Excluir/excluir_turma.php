<?php
session_start();
include '../conexao.php'; // <-- Caminho correto

if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
    header("Location: ../../Front/pages/Admin/admin.php");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../../Front/pages/Admin/ver_turmas.php?erro=ID inválido");
    exit;
}

$id = intval($_GET['id']);

// 1. Verifica se existem alunos vinculados
$checkAlunos = $conn->prepare("SELECT COUNT(*) AS total FROM alunos WHERE turma_id = ?");
$checkAlunos->bind_param("i", $id);
$checkAlunos->execute();
$resAlunos = $checkAlunos->get_result()->fetch_assoc();

if ($resAlunos['total'] > 0) {
    header("Location: ../../Front/pages/Admin/ver_turmas.php?erro=A turma tem alunos vinculados");
    exit;
}

// 2. Apaga os treinos vinculados
$delTreinos = $conn->prepare("DELETE FROM treinos WHERE turma_id = ?");
$delTreinos->bind_param("i", $id);
$delTreinos->execute();

// 3. Apaga jogos vinculados
$delJogos = $conn->prepare("DELETE FROM jogos WHERE turma_id = ?");
$delJogos->bind_param("i", $id);
$delJogos->execute();

// 4. Agora pode excluir a turma
$sql = $conn->prepare("DELETE FROM turmas WHERE id = ?");
$sql->bind_param("i", $id);

if ($sql->execute()) {
    header("Location: ../../Front/pages/Admin/ver_turmas.php?sucesso=Turma excluída com sucesso!");
    exit;
} else {
    header("Location: ../../Front/pages/Admin/ver_turmas.php?erro=Erro ao excluir a turma");
    exit;
}
