<?php
session_start();

// Caminho correto para o seu arquivo de conexão
require_once __DIR__ . '/../../../Back/conexao.php';

// Verifica se é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: loginAluno.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

// Consulta na tabela alunos
$sql = "SELECT id AS aluno_id, turma_id, nome, email, senha
        FROM alunos
        WHERE email = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro na preparação da query: " . $conn->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

if ($res && $res->num_rows === 1) {
    $u = $res->fetch_assoc();
    $senha_db = $u['senha'];

    // Verifica a senha: texto puro, md5 ou password_hash
    $ok = false;
    if ($senha === $senha_db) {
        $ok = true;
    } elseif (md5($senha) === $senha_db) {
        $ok = true;
    } elseif (password_verify($senha, $senha_db)) {
        $ok = true;
    }

    if ($ok) {
        // Cria sessão
        $_SESSION['aluno_id']    = (int)$u['aluno_id'];
        $_SESSION['aluno_nome']  = $u['nome'];
        $_SESSION['aluno_email'] = $u['email'];
        $_SESSION['turma_id']    = $u['turma_id'];

        header('Location: home_aluno.php');
        exit;
    }
}

// Falhou
$_SESSION['erro_login'] = 'E-mail ou senha incorretos.';
header('Location: loginAluno.php');
exit;
?>
