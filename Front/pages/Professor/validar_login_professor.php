<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once __DIR__ . '/../../../Back/conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /Front/pages/Professor/loginProfessor.php");
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if ($email === '' || $senha === '') {
    $_SESSION['erro_login'] = 'E-mail e senha são obrigatórios.';
    header("Location: /Front/pages/Professor/loginProfessor.php");
    exit;
}

$sql = "SELECT id AS professor_id, nome, email, senha
        FROM professores
        WHERE email = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    $_SESSION['erro_login'] = 'Erro na query: ' . $conn->error;
    header("Location: /Front/pages/Professor/loginProfessor.php");
    exit;
}

$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $senha_db = $row['senha'];

    $ok = false;
    if (password_verify($senha, $senha_db)) {
        $ok = true;
    } elseif ($senha === $senha_db) {
        $ok = true;
    } elseif (md5($senha) === $senha_db) {
        $ok = true;
    }

    if ($ok) {
        $_SESSION['professor_id']   = (int)$row['professor_id'];
        $_SESSION['professor_nome'] = $row['nome'];

        // Ajustando o link para o redirecionamento correto
        $redirect = 'http://localhost/NFSite/Front/pages/professor/home_professor.php';
        header("Location: $redirect");
        exit;
    } else {
        $_SESSION['erro_login'] = 'Senha incorreta.';
        header("Location: /Front/pages/Professor/loginProfessor.php");
        exit;
    }
} else {
    $_SESSION['erro_login'] = 'E-mail não encontrado.';
    header("Location: /Front/pages/Professor/loginProfessor.php");
    exit;
}
