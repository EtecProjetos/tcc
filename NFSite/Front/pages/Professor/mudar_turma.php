<?php
include '../../../back/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aluno_id = $_POST['aluno_id'] ?? null;
    $nova_turma = $_POST['nova_turma'] ?? null;

    if (!$aluno_id || !$nova_turma) {
        echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
        exit;
    }

    // Busca turma atual para evitar mudança desnecessária
    $stmt = $conn->prepare("SELECT turma_id FROM alunos WHERE id = ?");
    $stmt->bind_param("i", $aluno_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $aluno = $result->fetch_assoc();

    if (!$aluno) {
        echo json_encode(['success' => false, 'message' => 'Aluno não encontrado']);
        exit;
    }

    if ($aluno['turma_id'] == $nova_turma) {
        echo json_encode(['success' => false, 'message' => 'A turma selecionada é a mesma atual']);
        exit;
    }

    $update = $conn->prepare("UPDATE alunos SET turma_id = ? WHERE id = ?");
    $update->bind_param("ii", $nova_turma, $aluno_id);
    $success = $update->execute();

    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false, 'message' => 'Método inválido']);
}
