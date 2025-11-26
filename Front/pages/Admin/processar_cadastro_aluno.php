<?php
session_start();
include '../../../Back/conexao.php';

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
if (empty($nome) || empty($data_nascimento) || empty($cpf) || empty($senha)) {
    $_SESSION['erro_cadastro'] = "Preencha todos os campos obrigatórios.";
    header("Location: CriarContaAluno.php");
    exit;
}

// Verifica duplicidade
$camposUnicos = [
    'cpf' => $cpf,
    'cpf_responsavel' => $cpf_resp,
    'telefone' => $telefone
];

foreach ($camposUnicos as $col => $valor) {
    if (!empty($valor)) {
        $stmt = $conn->prepare("SELECT id FROM alunos WHERE $col = ?");
        $stmt->bind_param("s", $valor);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows > 0) {
            $_SESSION['erro_cadastro'] = ucfirst(str_replace("_", " ", $col)) . " já cadastrado!";
            header("Location: CriarContaAluno.php");
            exit;
        }
    }
}

// Gera hash da senha
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
    // Sucesso → redireciona para home_admin
    header("Location: home_admin.php");
    exit;
} else {
    $_SESSION['erro_cadastro'] = "Erro no cadastro: " . $conn->error;
    header("Location: CriarContaAluno.php");
    exit;
}
