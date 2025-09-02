<?php
session_start();
include '../Back/conexao.php';

$nome            = $_POST['nome'];
$data_nascimento = $_POST['data_nascimento'];
$cpf             = $_POST['cpf'];
$email           = $_POST['email'];
$senha           = $_POST['senha'];
$confirmar_senha = $_POST['confirmar_senha'];
$telefone        = $_POST['telefone'];

// Verifica se senhas conferem
if ($senha !== $confirmar_senha) {
    $_SESSION['erro_cadastro'] = "As senhas não coincidem!";
    header("Location: CriarContaProfessor.php");
    exit;
}

// Verifica se já existe email ou cpf
$sqlCheck = "SELECT * FROM professores WHERE email = ? OR cpf = ?";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("ss", $email, $cpf);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows > 0) {
    $_SESSION['erro_cadastro'] = "Já existe um professor cadastrado com esse e-mail ou CPF!";
    header("Location: CriarContaProfessor.php");
    exit;
}

// Criptografa a senha
$senhaHash = password_hash($senha, PASSWORD_BCRYPT);

// Insere no banco
$sql = "INSERT INTO professores (nome, data_nascimento, cpf, email, senha, telefone) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $nome, $data_nascimento, $cpf, $email, $senhaHash, $telefone);

if ($stmt->execute()) {
    $_SESSION['sucesso_cadastro'] = "Conta criada com sucesso! Faça login.";
    header("Location: loginProfessor.php");
    exit;
} else {
    $_SESSION['erro_cadastro'] = "Erro ao cadastrar: " . $stmt->error;
    header("Location: CriarContaProfessor.php");
    exit;
}
