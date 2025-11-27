<?php
session_start();

// Caminho correto para o arquivo de conexão
require_once __DIR__ . '/../../../Back/conexao.php';

// Verifica se é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: loginProfessor.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');

// Consulta na tabela professores
$sql = "SELECT id AS professor_id, nome, email, senha
        FROM professores
        WHERE email = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro na query: " . $conn->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

if ($res && $res->num_rows === 1) {
    $p = $res->fetch_assoc();
    $senha_db = $p['senha'];

    // Verifica a senha em 3 formatos
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
        $_SESSION['professor_id']    = (int)$p['professor_id'];
        $_SESSION['professor_nome']  = $p['nome'];
        $_SESSION['professor_email'] = $p['email'];

        header('Location: home_professor.php');
        exit;
    }
}

// Falhou
$_SESSION['erro_login'] = 'E-mail ou senha incorretos.';
header('Location: loginProfessor.php');
exit;
?>
