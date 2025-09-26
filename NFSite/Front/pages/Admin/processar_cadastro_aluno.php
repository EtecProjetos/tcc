<?php
session_start();
require_once __DIR__ . '/../Back/conexao.php';

// Só aceita POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: CriarContaAluno.php");
    exit;
}

// Captura e sanitiza
$nome            = ucfirst(trim($_POST['nome'] ?? ''));
$data_nascimento = $_POST['data_nascimento'] ?? null;
$cpf             = trim($_POST['cpf'] ?? '');
$email           = strtolower(trim($_POST['email'] ?? ''));
$senha           = $_POST['senha'] ?? '';
$telefone        = trim($_POST['telefone'] ?? null);
$nome_resp       = ucfirst(trim($_POST['nome_responsavel'] ?? ''));
$cpf_resp        = trim($_POST['cpf_responsavel'] ?? null);
$turma_id        = $_POST['turma_id'] ?? null;

// Validação mínima
if (empty($nome) || empty($data_nascimento) || empty($cpf) || empty($email) || empty($senha)) {
    $_SESSION['erro_cadastro'] = "Preencha todos os campos obrigatórios.";
    header("Location: CriarContaAluno.php");
    exit;
}

// Gera senha com password_hash
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Insere no banco
$sql = "INSERT INTO alunos 
        (nome, data_nascimento, cpf, email, senha, telefone, nome_responsavel, cpf_responsavel, turma_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssi", 
    $nome, 
    $data_nascimento, 
    $cpf, 
    $email, 
    $senha_hash, 
    $telefone, 
    $nome_resp, 
    $cpf_resp, 
    $turma_id
);

if ($stmt->execute()) {
    $_SESSION['cadastro_ok'] = "Conta criada com sucesso! Faça login.";
    header("Location: loginAluno.php");
    exit;
} else {
    if ($conn->errno === 1062) {
        $_SESSION['erro_cadastro'] = "E-mail ou CPF já cadastrados.";
    } else {
        $_SESSION['erro_cadastro'] = "Erro no cadastro: " . $conn->error;
    }
    header("Location: CriarContaAluno.php");
    exit;
}
