<?php
include '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    $nome = ucfirst(strtolower($_POST['nome']));
    $data_nascimento = $_POST['data_nascimento'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $nome_responsavel = ucfirst(strtolower($_POST['nome_responsavel']));
    $cpf_responsavel = $_POST['cpf_responsavel'];
    $turma_id = $_POST['turma_id'];

    $sql = "UPDATE alunos SET 
                nome = ?, 
                data_nascimento = ?, 
                email = ?, 
                telefone = ?, 
                nome_responsavel = ?, 
                cpf_responsavel = ?, 
                turma_id = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo "Erro ao preparar: " . $conn->error;
        exit;
    }

    $stmt->bind_param(
        "ssssssii",
        $nome,
        $data_nascimento,
        $email,
        $telefone,
        $nome_responsavel,
        $cpf_responsavel,
        $turma_id,
        $id
    );

    if ($stmt->execute()) {
        echo "OK";
    } else {
        echo "Erro ao salvar: " . $stmt->error;
    }

    $stmt->close();
    exit;
}

echo "Método inválido";
