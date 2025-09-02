<?php
session_start();
include '../Back/conexao.php';

$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM professores WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $professor = $result->fetch_assoc();

    if (password_verify($senha, $professor['senha']) || $senha === $professor['senha']) {
        $_SESSION['professor_id'] = $professor['id'];
        $_SESSION['professor_nome'] = $professor['nome'];

        header("Location: home_professor.php");
        exit;
    } else {
        $_SESSION['erro_login'] = "Senha incorreta!";
        header("Location: loginProfessor.php");
        exit;
    }
} else {
    $_SESSION['erro_login'] = "Email não encontrado!";
    header("Location: loginProfessor.php");
    exit;
}
?>


