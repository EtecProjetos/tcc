<?php
session_start();
include '../../../Back/conexao.php';

// Só aceita POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: criar_conta_professor.php");
    exit;
}

// Captura e sanitiza
$nome            = ucfirst(trim($_POST['nome'] ?? ''));
$data_nascimento = $_POST['data_nascimento'] ?? null;
$cpf             = trim($_POST['cpf'] ?? '');
$email           = strtolower(trim($_POST['email'] ?? ''));
$senha           = $_POST['senha'] ?? '';
$telefone        = trim($_POST['telefone'] ?? null);

// Validação mínima
if (empty($nome) || empty($data_nascimento) || empty($cpf) || empty($senha)) {
    $_SESSION['erro_cadastro'] = "Preencha todos os campos obrigatórios.";
    header("Location: criar_conta_professor.php");
    exit;
}

// Checar duplicidade: cpf e telefone
$sql_check = "SELECT * FROM professores WHERE cpf = ? OR telefone = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ss", $cpf, $telefone);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if($result_check->num_rows > 0){
    $_SESSION['erro_cadastro'] = "CPF ou telefone já cadastrados.";
    header("Location: CriarContaprofessor.php");
    exit;
}

// Gera hash da senha
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Insere no banco
$sql_insert = "INSERT INTO professores (nome, data_nascimento, cpf, email, senha, telefone) VALUES (?, ?, ?, ?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("ssssss", $nome, $data_nascimento, $cpf, $email, $senha_hash, $telefone);

if($stmt_insert->execute()){
    $_SESSION['cadastro_ok'] = "Conta criada com sucesso!";
    header("Location: home_admin.php"); // redireciona para home_admin após sucesso
    exit;
}else{
    $_SESSION['erro_cadastro'] = "Erro ao criar conta: ".$conn->error;
    header("Location: criar_conta_professor.php");
    exit;
}
