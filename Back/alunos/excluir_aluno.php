<?php
include '../conexao.php';

if (!isset($_GET['id'])) {
    echo "ID inválido";
    exit;
}

$id = intval($_GET['id']);

// Desativa restrições temporariamente (caso existam frequências, convocações, etc.)
$conn->query("SET FOREIGN_KEY_CHECKS = 0");

$sql = "DELETE FROM alunos WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "Erro: " . $conn->error;
    exit;
}

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "OK";
} else {
    echo "Erro ao excluir: " . $stmt->error;
}

// Reativa as FKs
$conn->query("SET FOREIGN_KEY_CHECKS = 1");

$stmt->close();
exit;
