<?php
session_start();
include '../Back/conexao.php';

// Verifica se tem token
if(!isset($_GET['token'])){
    echo "Token inválido!";
    exit;
}

$token = $_GET['token'];

// Busca token válido
$sql = "SELECT * FROM senha_reset WHERE token = ? AND usado = 0 AND expiracao > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    echo "Token inválido ou expirado!";
    exit;
}

$resetData = $result->fetch_assoc();

// Se enviar nova senha
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $senha = $_POST['senha'];
    $confirmar = $_POST['confirmar'];

    if($senha !== $confirmar){
        $_SESSION['msg'] = "As senhas não coincidem!";
        header("Location: recuperar_senha.php?token=$token");
        exit;
    }

    $senhaHash = password_hash($senha, PASSWORD_BCRYPT);
    $tabela = $resetData['tipo'] == "professor" ? "professores" : "alunos";

    // Atualiza senha
    $sqlUpdate = "UPDATE $tabela SET senha = ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("si", $senhaHash, $resetData['usuario_id']);
    $stmtUpdate->execute();

    // Marca token como usado
    $sqlToken = "UPDATE senha_reset SET usado = 1 WHERE id = ?";
    $stmtToken = $conn->prepare($sqlToken);
    $stmtToken->bind_param("i", $resetData['id']);
    $stmtToken->execute();

    $_SESSION['msg'] = "Senha redefinida com sucesso! Faça login.";
    header("Location: loginProfessor.php"); // ou loginAluno.php
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Redefinir Senha</title>
</head>
<body>
  <h2>Defina sua nova senha</h2>

  <?php
  if(isset($_SESSION['msg'])){
      echo "<p style='color:red'>".$_SESSION['msg']."</p>";
      unset($_SESSION['msg']);
  }
  ?>

  <form method="POST">
      <label>Nova Senha:</label><br>
      <input type="password" name="senha" required><br><br>

      <label>Confirmar Senha:</label><br>
      <input type="password" name="confirmar" required><br><br>

      <button type="submit">Redefinir Senha</button>
  </form>
</body>
</html>
